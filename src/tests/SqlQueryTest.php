<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use MyApp\DB\SqlBuilder;

class SqlQueryTest extends TestCase {

  public function testクラスのインスタンスを生成できる()
  {
    $sqlQuery = new SqlBuilder();
    $this->assertSame(is_a($sqlQuery, SqlBuilder::class), true);
  }

  public function testクエリタイプを指定するメソッドは必須()
  {
    $sqlQuery = new SqlBuilder();
    $msg = '';
    try {
      $query = $sqlQuery->getQuery();
    } catch (\Exception $e) {
      $msg = $e->getMessage();
    }
    $this->assertSame($msg, 'select, create, update, deleteのいずれか一つをメソッドチェーンに追加する必要があります');
  }

  public function testINSERT文が生成できる()
  {
    $fields = ['id', 'name', 'age'];
    $values = [
      ['1', 'taro', '20'],
      ['2', 'foo', '30'],
      ['3', 'bar', '40'],
    ];
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->create($fields, $values)->getQuery();
    $this->assertSame($query, "INSERT INTO test (id, name, age) VALUES (1, taro, 20),\n(2, foo, 30),\n(3, bar, 40);");
  }

  public function testUPDATE文が生成できる()
  {
    $sets = [
      'id' => '1',
      'name' => 'taro',
      'age' => '20',
    ];
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->update($sets)->getQuery();
    $this->assertSame($query, 'UPDATE test SET id = 1, name = taro, age = 20;');
  }

  public function testUPDATE文でWHERE指定できる()
  {
    $sets = [
      'id' => '1',
      'name' => 'taro',
      'age' => '20',
    ];
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->update($sets)->where(['id' => '10'])->getQuery();
    $this->assertSame($query, 'UPDATE test SET id = 1, name = taro, age = 20 WHERE id = 10;');
  }

  public function testDELETE文が生成できる()
  {
    $sets = [
      'id' => '1',
      'name' => 'taro',
      'age' => '20',
    ];
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->delete()->getQuery();
    $this->assertSame($query, 'DELETE FROM test;');
  }

  public function testDELETE文でWHERE指定ができる()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->delete()->where(['id' => '10'])->getQuery();
    $this->assertSame($query, 'DELETE FROM test WHERE id = 10;');
  }

  public function testSELECT文が生成できる()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->getQuery();
    $this->assertSame($query, 'SELECT * FROM test;');
  }

  public function testクエリタイプを指定するメソッドを重複して呼ぶと例外を返す()
  {
    $sqlQuery = new SqlBuilder();
    $msg = '';
    try {
      $query = $sqlQuery->select()->select()->getQuery();
    } catch (\Exception $e) {
      $msg = $e->getMessage();
    }
    $this->assertSame($msg, 'クエリタイプを指定するメソッドは重複してメソッドチェーンに追加できません');
  }

  public function testSELECT文でWHERE生成できる()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->where(['id' => '10'])->getQuery();
    $this->assertSame($query, 'SELECT * FROM test WHERE id = 10;');
  }

  public function testSELECT文にてLIMITで取得件数を限定するSQL文を出力することができる()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->where(['id' => '10'])->limit(5)->getQuery();
    $this->assertSame($query, 'SELECT * FROM test WHERE id = 10 LIMIT 5;');

    // whereとlimitの順番が関係ない
    $sqlQuery2 = new SqlBuilder();
    $query2 = $sqlQuery2->table('test')->select()->limit(5)->where(['id' => '10'])->getQuery();
    $this->assertSame($query2, 'SELECT * FROM test WHERE id = 10 LIMIT 5;');
  }

  public function testSELECT文で表示順を指定することができる()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->limit(5)->where(['id' => '10'])->order('id', 'ASC')->getQuery();
    $this->assertSame($query, 'SELECT * FROM test WHERE id = 10 ORDER BY id ASC LIMIT 5;');

  }

  public function testORDERBYのデフォルトがASCであること()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->limit(5)->where(['id' => '10'])->order('id')->getQuery();
    $this->assertSame($query, 'SELECT * FROM test WHERE id = 10 ORDER BY id ASC LIMIT 5;');
  }

  public function testORDERBYでDESCが指定できること()
  {
    $sqlQuery = new SqlBuilder();
    $query = $sqlQuery->table('test')->select()->limit(5)->where(['id' => '10'])->order('id', 'DESC')->getQuery();
    $this->assertSame($query, 'SELECT * FROM test WHERE id = 10 ORDER BY id DESC LIMIT 5;');
  }

  public function testORDERBYでASCとDESC以外を指定したときにれ外が発生すること()
  {
    $sqlQuery = new SqlBuilder();
    $msg = '';
    try {
      $query = $sqlQuery->table('test')->select()->limit(5)->where(['id' => '10'])->order('id', 'DUMMY')->getQuery();
    } catch (\Exception $e) {
      $msg = $e->getMessage();
    }
    $this->assertSame($msg, '不正なorderオプションが渡されました');
  }

}
