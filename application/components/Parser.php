<?php

/**
 * Description of Parser
 *
 * @author Vadym007
 */
require_once 'phpQuery/phpQuery/phpQuery.php';

class Parser 
{
    private static function curl_get($url, $referer = 'https://www.google.ru/?hl=ru&gws_rd=ssl') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36 OPR/52.0.2871.99');
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private static function parsePage($url) {
        $db = Database::getInstance();
        $data = array();
        $html = self::curl_get($url);
        $doc = phpQuery::newDocumentHTML($html, $charset = 'utf-8');
        $data['name'] = $doc->find('.product-h2')->text();
        $data['price'] = $doc->find(".product-sizes input:gt(2)")->attr('value');
        $data['wholesale_price'] = $doc->find(".product-sizes input:gt(3)")->attr('value');
        $data['availability'] = 1; //на сайте отсутствует информация о наличии товара
        $data['color'] = str_replace('Цвет: ', '', $doc->find(".product-text p:first")->text());
        $data['description'] = $doc->find('.product-text2 p span')->text();
           
        $sizes = $doc->find('.product-sizes');
            foreach($sizes as $size) {
                $pqSize = pq($size);
                
                if($pqSize->attr('style') != "display:none") {
                    $data['size'] = $pqSize->find('li')->text();
                    break;
                }
            }
        

        $photos = $doc->find('div.product-image div.product-image-color');
            foreach($photos as $photo) {
                $pqPhoto = pq($photo);
                if($pqPhoto->attr('style') === "position:relative;" ) {
                    $data['photo'] = $pqPhoto->find('a:last')->attr('href');
                    $data['code'] = substr($pqPhoto->attr('id'), -5);
                    break;
                }  
            }
        $data['url'] = $url;
        $db->insert('goods', $data);
    }

    public static function getData($url) {
        $data = array();
        $currentPage = $url;
        while(true) {
            $html = self::curl_get($currentPage);
            $document = phpQuery::newDocumentHTML($html, $charset = 'utf-8');
            $nextPage = 'https://glem.com.ua/' . $document->find('.split-pages a:last')->attr('href');

            $links = $document->find('.list-products-holder li .list-products-name a');

            foreach ($links as $link) {
                $pqLink = pq($link); //pq делает объект phpQuery
                $link = 'https://glem.com.ua/' . $pqLink->attr('href');
                $data[] = self::parsePage($link);
            }

            if((int)preg_replace('/.*=/', '', $nextPage) < (int)preg_replace('/.*=/', '', $currentPage)) {
                return true;
            }
            $currentPage = $nextPage;
        }
    }

}
