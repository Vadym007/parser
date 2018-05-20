<?php

class Controller_Put extends Controller
{
    public function __construct() {
        $this->view = new View();
    }

    public function action_index() 
    {
        $parser = new Parser;
        $parser ->set(CURLOPT_HEADER, 0)
                ->set(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36 OPR/52.0.2871.99')
                ->set(CURLOPT_REFERER, 'https://www.google.ru/?hl=ru&gws_rd=ssl')
                ->set(CURLOPT_RETURNTRANSFER, 1);
        
        if($this->getData("https://glem.com.ua/zhenskie-platya-optom/", $parser)){
            $this->view->generate('parse_view.php', 'template_view.php');
        } else {
            $this->view->generate('error_view.php', 'template_view.php');
        }
    }
    
    
    private function parsePage($url, Parser $parser) 
    {
        $db = Database::getInstance();
        $data = array();
        $html = $parser->exec($url);
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

    private function getData($url, Parser $parser) 
    {
        $data = array();
        $currentPage = $url;
        while(true) {
            $html = $parser->exec($currentPage);
            $document = phpQuery::newDocumentHTML($html, $charset = 'utf-8');
            $nextPage = 'https://glem.com.ua/' . $document->find('.split-pages a:last')->attr('href');

            $links = $document->find('.list-products-holder li .list-products-name a');

            foreach ($links as $link) {
                $pqLink = pq($link); //pq делает объект phpQuery
                $link = 'https://glem.com.ua/' . $pqLink->attr('href');
                $this->parsePage($link, $parser);
            }

            if((int)preg_replace('/.*=/', '', $nextPage) < (int)preg_replace('/.*=/', '', $currentPage)) {
                return true;
            }
            $currentPage = $nextPage;
        }
    }
    
    
}