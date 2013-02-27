<?php
/**
 * WebHDFSクラス
 *
 * @package         WebHDFS
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 */
class WebHDFS {
    /**
     * WebHDFS Format
     * @const string
     */
    const FORMAT = 'http://%s:%d/webhdfs/v1%s?%s';

    /**
     * ホスト名
     * @var string
     */
    private $_host;

    /**
     * ポート番号
     * @var integer
     */
    private $_port;

    /**
     * コンストラクタ
     *
     * @access public
     * @$host string $host
     * @$port string $port
     * @return void
     */
    public function __construct($host='localhost', $port='50070') {
        $this->_host = $host;
        $this->_port = $port;
    }

    /**
     * Create and Write to a File
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#CREATE
     *
     * @access public
     * @param string $path     HDFSファイルパス
     * @param string $body     データ
     * @param array  $options  オプション
     *  - overwrite   : <true|false>
     *  - blocksize   : <LONG>
     *  - replication : <SHORT>
     *  - permission  : <OCTAL>
     *  - buffersize  : <INT>
     * @return boolean
     */
    public function put($path, $body, $options=null) {
        $options['op'] = 'CREATE';
        $code = $this->_request('Field', 'PUT', $path, $options, $body);

        return (201 == $code);
    }

    /**
     * Append to a File
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#APPEND
     *
     * @access public
     * @param string  $path       HDFSファイルパス
     * @param string  $body       データ
     * @param integer $buffersize サイズ
     * @return boolean
     */
    public function append($path, $body, $buffersize=null) {
        $buffersize = empty($buffersize) ? strlen($body) : $buffersize;
        $code = $this->_request(
            'Field', 'POST', $path,
            array(
                'op'         => 'APPEND',
                'buffersize' => $buffersize
            )
        );

        return (200 == $code);
    }

    /**
     * Open and Read a File
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#OPEN
     *
     * @access public
     * @param string $path     HDFSファイルパス
     * @param string $body     データ
     * @param array  $options  オプション
     *  - offset     : <LONG>
     *  - length     : <LONG>
     *  - buffersize : <INT>
     * @return mixed
     */
    public function cat($path, $options=null) {
        $options['op']   = 'OPEN';
        $result = $this->_request('Buff', 'GET', $path, $options);

        return $result;
    }


    /**
     * Make a Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#MKDIRS
     *
     * @access public
     * @param string  $path       HDFSファイルパス
     * @param integer $permission パーミッション
     * @return boolean
     */
    public function mkdir($path, $permission=755) {
        $result = $this->_request(
            'JSON', 'PUT', $path,
            array(
                'op'         => 'MKDIRS',
                'permission' => $permission
            )
        );

        return !empty($result['boolean']);
    }

    /**
     * Rename a File/Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#RENAME
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @param string $dest 変更先
     * @param array  $options
     * @return boolean
     */
    public function mv($path, $dest) {
        $result = $this->_request(
            'JSON', 'PUT', $path,
            array(
                'op'          => 'RENAME',
                'destination' => $dest
            )
        );

        return !empty($result['boolean']);
    }

    /**
     * Delete a File/Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#DELETE
     *
     * @access public
     * @param string  $path      HDFSファイルパス
     * @param boolean $recursive 再帰フラグ
     * @return boolean
     */
    public function rm($path, $recursive=false) {
        $result = $this->_request(
            'JSON', 'DELETE', $path,
            array(
                'op'        => 'DELETE',
                'recursive' => $recursive
            )
        );

        return !empty($result['boolean']);
    }

    /**
     * Status of a File/Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#GETFILESTATUS
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @return mixed
     */
    public function stat($path) {
        $result = $this->_request(
            'JSON', 'GET', $path,
            array(
                'op' => 'GETFILESTATUS'
            )
        );

        return $result;
    }

    /**
     * List a Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#LISTSTATUS
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @return mixed
     */
    public function ls($path) {
        $result = $this->_request(
            'JSON', 'GET', $path,
            array(
                'op' => 'LISTSTATUS'
            )
        );

        return $result;
    }

    /**
     * Get Content Summary of a Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#GETCONTENTSUMMARY
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @return mixed
     */
    public function summary($path) {
        $result = $this->_request(
            'JSON', 'GET', $path,
            array(
                'op' => 'GETCONTENTSUMMARY'
            )
        );

        return $result;
    }

    /**
     * Get File Checksum
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#GETFILECHECKSUM
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @return mixed
     */
    public function checksum($path) {
        $result = $this->_request(
            'JSON', 'GET', $path,
            array(
                'op' => 'GETFILECHECKSUM'
            )
        );

        return $result;
    }

    /**
     * Get Home Directory
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#GETHOMEDIRECTORY
     *
     * @access public
     * @return string
     */
    public function homedir() {
        $result = $this->_request(
            'JSON', 'GET', '',
            array(
                'op' => 'GETHOMEDIRECTORY'
            )
        );

        return empty($result['Path']) ? '':$result['Path'];
    }

    /**
     * Set Permission
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#SETPERMISSION
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @param string $mode パーミッション
     * @return boolean
     */
    public function chmod($path, $mode) {
        $code = $this->_request(
            'Code', 'PUT', $path,
            array(
                'op'         => 'SETPERMISSION',
                'permission' => $mode
            )
        );

        return (200 == $code);
    }

    /**
     * Set Owner
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#SETOWNER
     *
     * @access public
     * @param string $path  HDFSファイルパス
     * @param string $owner オーナー名
     * @param string $group グループ名
     * @return boolean
     */
    public function chown($path, $owner, $group=null) {
        $options = array(
            'op'    => 'SETOWNER',
            'owner' => $owner
        );
        if (!empty($group)) {
            $options['group'] = $group;
        }
        $code = $this->_request('Code', 'PUT', $path, $options);

        return (200 == $code);
    }

    /**
     * Set Replication Factor
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#SETREPLICATION
     *
     * @access public
     * @param string  $path        HDFSファイルパス
     * @param integer $replication 番号
     * @return boolean
     */
    public function replication($path, $replication) {
        $code = $this->_request(
            'Code', 'PUT', $path,
            array(
                'op'          => 'SETREPLICATION',
                'replication' => $replication
            )
        );

        return (200 == $code);
    }

    /**
     * Set Access or Modification Time
     * @link http://hadoop.apache.org/docs/r1.0.4/webhdfs.html#SETTIMES
     *
     * @access public
     * @param string $path HDFSファイルパス
     * @param array  $options
     *  - modificationtime : <TIME>
     *  - accesstime       : <TIME>
     * @return boolean
     */
    public function touch($path, $options=null) {
        $code = $this->_request(
            'Code', 'PUT', $path,
            array(
                'op' => 'SETTIMES'
            )
        );

        return (200 == $code);
    }

    /**
     * cURL リクエスト発行
     *
     * @access public
     * @param string $way    指定関数
     * @param string $method HTTPメソッド
     * @param string $path   パス
     * @param array  $params 引数
     * @param string $buff   バッファ
     * @return boolean
     */
    private function _request($way, $method, $path, $params, $buff=null) {
        $url = sprintf(
            self::FORMAT, 
            $this->_host, 
            $this->_port, 
            $path, 
            http_build_query($params)
        );
        $request = include dirname(__FILE__).'/HTTP/Request.php';
        return $request($way, $url, $method, $buff);
    }
} // class WebHDFS 
