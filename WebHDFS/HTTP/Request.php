<?php
/**
 * Request関数
 *
 * @package         WebHDFS
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 */
$funcs = array(
    /**
     * データ送信
     *
     * @access public
     * @param resource $curl cURLリソース
     * @param string   $buff バッファー
     * @return integer HTTPコード
     */
    'Field'=> function (&$curl, $buff) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        $header = curl_exec($curl);
        $code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (307 != $code) {
            return 400;
        }
        $matches = array();
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $newurl = trim($matches[1]);

        curl_setopt($curl, CURLOPT_URL,        $newurl);
        curl_setopt($curl, CURLOPT_INFILESIZE, strlen($buff));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/octet-stream'));
        curl_setopt($curl, CURLOPT_POST,       true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $buff);

        $html = curl_exec($curl);

        return curl_getinfo($curl, CURLINFO_HTTP_CODE);
    },

    /**
     * バッファーの取得
     *
     * @access public
     * @param resource $curl cURLリソース
     * @param string   $buff ダミー
     * @return string データ 
     */
    'Buff'=> function (&$curl, $buff) {
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $buff = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (200 != $code) {
            return null;
        }
        return $buff;
    },

    /**
     * JSON取得
     *
     * @access public
     * @param resource $curl cURLリソース
     * @param string   $buff ダミー
     * @return array JSONデータ
     */
    'JSON'=> function (&$curl, $buff) {
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);         
        $json = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (200 != $code) {
            return 400;
        }
        return json_decode($json, true);
    },

    /**
     * PUT処理
     *
     * @access public
     * @param resource $curl cURLリソース
     * @param string   $buff ダミー
     * @return integer HTTPコード
     */
    'Code'=> function (&$curl, $buff) {
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);         
        $json = curl_exec($curl);
        return curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }
);

/**
 * cURL リクエスト発行
 *
 * @access public
 * @param string $way    指定関数
 * @param string $url    URL
 * @param string $method HTTPメソッド
 * @param string $buff   バッファ
 * @return mixed
 */
return function ($way, $url, $method, $buff=null) use ($funcs) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,            $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  $method);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);

    $result = $funcs[$way]($curl, $buff);

    curl_close($curl);

    return $result;
};

