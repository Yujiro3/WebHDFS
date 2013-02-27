WebHDFS PHPクライアント
======================

利用方法
------

### ホームディレクトリの取得 ###

```php
<?php
require_once './WebHDFS.php';

/* ホストとポートを指定する */
$hdfs = new WebHDFS('localhost', 50700);

echo $hdfs->homedir();
```

### 出力結果 ###
    
    /user/webuser
    

### 新規ファイルの作成 ###

```php
<?php
require_once './WebHDFS.php';

$hdfs = new WebHDFS();

$hdfs->put('/user/webuser/sample.txt', "sample\ntest\n");
```

### ファイルの読み込み ###

```php
<?php
require_once './WebHDFS.php';

/* ホストを指定する */
$hdfs = new WebHDFS('localhost');

echo $hdfs->cat('/user/webuser/sample.txt');
```

### 出力結果 ###
    
    sample
    test
    

ライセンス
----------
Copyright &copy; 2013 Yujiro Takahashi  
Licensed under the [MIT License][MIT].  
Distributed under the [MIT License][MIT].  

[MIT]: http://www.opensource.org/licenses/mit-license.php
