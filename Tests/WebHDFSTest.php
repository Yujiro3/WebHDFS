<?php
require_once './WebHDFS/WebHDFS.php';

/**
 * WebHDFSテストクラス
 *
 * @package         WebHDFS
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 */
class WebHDFSTest extends PHPUnit_Framework_TestCase {
    /**
     * WebHDFS
     * @var object
     */
    protected $hdfs;

    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->hdfs = new WebHDFS();
    }

    /**
     * testPut
     *
     * @access public
     * @return void
     */
    public function testPut() {
        $result = $this->hdfs->put('/user/webuser/sample.txt', "sample1\sample2\n");
        $this->assertTrue($result);
    }

    /**
     * testAppend
     *
     * @access public
     * @return void
     */
    public function testAppend() {
        $result = $this->hdfs->append('/user/webuser/sample.txt', "append1\append2\n");
        $this->assertTrue($result);
    }

    /**
     * testCat
     *
     * @access public
     * @return void
     */
    public function testCat() {
        $result = $this->hdfs->cat('/user/webuser/sample.txt');
        $this->assertEquals("sample1\sample2\nappend1\append2\n", $result);
    }

    /**
     * testMkdir
     *
     * @access public
     * @return void
     */
    public function testMkdir() {
        $result = $this->hdfs->mkdir('/user/webuser/newdir/');
        $this->assertTrue($result);
    }

    /**
     * testMv
     *
     * @access public
     * @return void
     */
    public function testMv() {
        $result = $this->hdfs->mv('/user/webuser/newdir/', '/user/webuser/newdir2/');
        $this->assertTrue($result);
    }

    /**
     * testRm
     *
     * @access public
     * @return void
     */
    public function testRm() {
        $result = $this->hdfs->rm('/user/webuser/newdir2/');
        $this->assertTrue($result);
    }

    /**
     * testStat
     *
     * @access public
     * @return void
     */
    public function testStat() {
        $result = $this->hdfs->stat('/user/webuser/sample.txt');
        $this->assertTrue($result);
    }

    /**
     * testLs
     *
     * @access public
     * @return void
     */
    public function testLs() {
        $result = $this->hdfs->ls('/user/webuser');

        $this->assertEquals("sample1\sample2\nappend1\append2\n", $result);
    }

    /**
     * testSummary
     *
     * @access public
     * @return void
     */
    public function testSummary() {
        $result = $this->hdfs->summary('/user/webuser/sample.txt');
        $this->assertEquals("sample1\sample2\nappend1\append2\n", $result);
    }

    /**
     * testChecksum
     *
     * @access public
     * @return void
     */
    public function testChecksum() {
        $result = $this->hdfs->checksum('/user/webuser/sample.txt');
        $this->assertEquals("sample1\sample2\nappend1\append2\n", $result);
    }

    /**
     * testHomedir
     *
     * @access public
     * @return void
     */
    public function testHomedir() {
        $result = $this->hdfs->homedir();
        $this->assertEquals('/user/webuser', $result);
    }

    /**
     * testChmod
     *
     * @access public
     * @return void
     */
    public function testChmod() {
        $result = $this->hdfs->chmod('/user/webuser/sample.txt', '0777');
        $this->assertTrue($result);
    }

    /**
     * testChown
     *
     * @access public
     * @return void
     */
    public function testChown() {
        $result = $this->hdfs->chown('/user/webuser/sample.txt', 'webuser');
        $this->assertTrue($result);
    }

    /**
     * testTouch
     *
     * @access public
     * @return void
     */
    public function testTouch() {
        $result = $this->hdfs->touch('/user/webuser/sample.txt');
        $this->assertTrue($result);
    }

} // class WebHDFSTest extends PHPUnit_Framework_TestCase
