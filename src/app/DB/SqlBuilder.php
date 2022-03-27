<?php
namespace MyApp\DB;

class SqlBuilder {

  private string $table = '';
  private string $queryType = '';
  private array $params = [];
  private array $values = []; // 2D array
  private array $where = [];
  private ?int $limit = null;
  private ?array $order = null;
  private array $sets = [];


  /**
   * メソッドチェーンを終わらせてSQL文を生成する
   * @return string
   */
  public function getQuery():string
  {
    return $this->buildSql();
  }

  /**
   * メソッドチェーン: テーブル名を指定する
   * @return SqlBuilder
   */
  public function table(string $table_name):SqlBuilder
  {
    $this->table = $table_name;
    return $this;
  }

  /**
   * メソッドチェーン: SELECT文を生成する
   * @return SqlBuilder
   */
  public function select(array $params = []):SqlBuilder
  {
    if ($this->queryType) throw new \Exception('クエリタイプを指定するメソッドは重複してメソッドチェーンに追加できません');
    $this->queryType = 'select';
    $this->params = $params;
    return $this;
  }

  /**
   * メソッドチェーン: CREATE文を生成する
   * @return SqlBuilder
   */
  public function create(array $params, array $values):SqlBuilder
  {
    if ($this->queryType) throw new \Exception('クエリタイプを指定するメソッドは重複してメソッドチェーンに追加できません');
    if (count($params) === 0) throw new \Exception('INSERT文には最低一つのカラムとバリューのセットを渡す必要があります');
    if (count($values) === 0) throw new \Exception('INSERT文には最低一つのカラムとバリューのセットを渡す必要があります');
    $this->params = $params;
    $this->values = $values;
    $this->queryType = 'create';
    return $this;
  }

  /**
   * メソッドチェーン: UPDATE文を生成する
   * @return SqlBuilder
   */
  public function update(array $sets):SqlBuilder
  {
    if ($this->queryType) throw new \Exception('クエリタイプを指定するメソッドは重複してメソッドチェーンに追加できません');
    if (count($sets) === 0) throw new \Exception('INSERT文には最低一つのカラムとバリューのセットを渡す必要があります');
    $this->sets = $sets;
    $this->queryType = 'update';
    return $this;
  }

  /**
   * メソッドチェーン: UPDATE文を生成する
   * @return SqlBuilder
   */
  public function delete():SqlBuilder
  {
    if ($this->queryType) throw new \Exception('クエリタイプを指定するメソッドは重複してメソッドチェーンに追加できません');
    $this->queryType = 'delete';
    return $this;
  }

  /**
   * メソッドチェーン: WHERE構文を生成する
   * @return SqlBuilder
   */
  public function where(array $where = []):SqlBuilder
  {
    $this->where = $where;
    return $this;
  }

  /**
   * メソッドチェーン: LIMIT構文を生成する
   * @return SqlBuilder
   */
  public function limit(?int $limit = null):SqlBuilder
  {
    if ($limit > 0) $this->limit = $limit;

    return $this;
  }

  /**
   * メソッドチェーン: ORDER BY構文を生成する
   * @return SqlBuilder
   */
  public function order(string $param, string $order = 'ASC'):SqlBuilder
  {
    $acceptableOrder = ['ASC', 'DESC'];
    if (array_search($order, $acceptableOrder) === false) throw new \Exception('不正なorderオプションが渡されました');
    $this->order = [
      'param' => $param,
      'order' => $order,
    ];
    return $this;
  }

  private function buildSql():string
  {
    if (!$this->queryType) throw new \Exception('select, create, update, deleteのいずれか一つをメソッドチェーンに追加する必要があります');
    switch ($this->queryType) {
      case 'create':
        return $this->buildInsertSql();
      case 'select':
        return $this->buildSelectSql();
      case 'update':
        return $this->buildUpdateSql();
      case 'delete':
        return $this->buildDeleteSql();
      default:
        return '';
    }
  }

  private function buildSelectSql():string
  {
    $sql = "SELECT __params__ FROM {$this->table}";

    $paramString = count($this->params) > 0 ? implode(', ', $this->params) : '*';
    $sql = str_replace('__params__', $paramString, $sql);


    if (count($this->where) > 0) {
      $whereString = $this->makeWhere();
      $sql .= " {$whereString}";
    }

    if ($this->order) {
      $orderString = $this->makeOrder();
      $sql .= " {$orderString}";
    }

    if ($this->limit) {
      $limitString = $this->makeLimit();
      $sql .= " {$limitString}";
    }

    $sql .= ';';
    return $sql;
  }

  private function buildInsertSql():string
  {
    $sql = "INSERT INTO {$this->table} __fields__ VALUES __values__";

    $fieldString = $this->makeInsertField();
    $sql = str_replace('__fields__', $fieldString, $sql);

    $valueString = $this->makeInsertValue();
    $sql = str_replace('__values__', $valueString, $sql);

    $sql .= ';';
    return $sql;
  }

  private function buildUpdateSql():string
  {
    $sql = "UPDATE {$this->table} SET __sets__";

    $setString = $this->makeUpdateSet();
    $sql = str_replace('__sets__', $setString, $sql);

    if (count($this->where) > 0) {
      $whereString = $this->makeWhere();
      $sql .= " {$whereString}";
    }

    $sql .= ';';
    return $sql;
  }

  private function buildDeleteSql():string
  {
    $sql = "DELETE FROM {$this->table}";

    if (count($this->where) > 0) {
      $whereString = $this->makeWhere();
      $sql .= " {$whereString}";
    }

    $sql .= ';';
    return $sql;
  }

  private function makeWhere():string
  {
    if (count($this->where) === 0) return '';

    $whereString = 'WHERE ';
    $conditionArray = [];
    foreach ($this->where as $field => $value) {
      $singleCondition = "{$field} = {$value}";
      $conditionArray[] = $singleCondition;
    }
    $consitionString = implode(' AND ', $conditionArray);
    $whereString .= $consitionString;
    return $whereString;
  }

  private function makeLimit():string
  {
    return "LIMIT {$this->limit}";
  }

  private function makeOrder():string
  {
    $param = $this->order['param'];
    $order = $this->order['order'];
    return "ORDER BY {$param} {$order}";
  }

  private function makeInsertField():string
  {
    return '(' . implode(', ', $this->params) . ')';
  }

  private function makeInsertValue():string
  {
    $valueStringArray = [];
    foreach ($this->values as $valueArr) {
      $valueStringArray[] = '(' . implode(', ', $valueArr) . ')';
    }
    return implode(",\n", $valueStringArray);
  }

  private function makeUpdateSet():string
  {
    $setStringArray = [];
    foreach ($this->sets as $key => $value) {
      $setStringArray[] = "{$key} = {$value}";
    }
    return implode(", ", $setStringArray);
  }
}
