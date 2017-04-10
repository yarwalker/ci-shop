<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog extends MX_Controller
{
    public //$tpl = 'catalog.tpl',
           $mname,
           $tag = 'CONTENT';

    protected $_dest_path = 'assets/upload/images/'; // путь для сохранения картинок товаров

    function __construct()
    {
        parent::__construct();
        $this->mname = strtolower(get_class());// imya modulya
        //$this->tag = strtoupper($this->mname); // TAG v shablone

        // загружаем модель для меню
        $this->load->model($this->mname . '/' . $this->mname . '_model');
    }

    public function index()
    {
        // кэшируем страницы на 60 минут
        $this->output->cache(60);

        $model = $this->mname . '_model';
        $catID = $this->uri->segment(2);

        // получаем все элементы категории
        $response = $this->$model->getItems($catID, NULL);

        $this->load->library('pagination');
        $config['base_url'] = base_url($this->mname . '/' . $catID);
        $config['total_rows'] = count($response);
        $config['per_page']   = 10;
        $config['num_links']  = 5;
        //$config['page_query_string'] = true;
        $this->pagination->initialize($config);

        // создаем пагинацию
        $a['item_pagination'] =  $this->pagination->create_links();

        // выбираем 10 товаров для отображения на странице
        $a['items'] = array_slice($response, ( $this->uri->segment(3) ? $this->uri->segment(3) : 0), $config['per_page']);

        // получаем картинки товаров категории
        $images = $this->$model->getItemsImages($catID, NULL);

        // получаем цены товаров категории
        $prices = $this->$model->getItemsAvail($catID, NULL);

        foreach($a['items'] as &$item):
            // подготовка изображений
            $item->Image = '/assets/upload/noimage.jpg';

            foreach($images as $img):
                if($img->SizeType == 's' && $img->ViewType == 'v' && $img->No == $item->No):
                    $item->Image = 'http://img.merlion.ru/items/' . $img->FileName; //$img_url;
                    break;
                endif;
            endforeach;

            $item->Price = 0;
            $item->Available = 0;
            foreach($prices as $price):
                if($price->No == $item->No):
                    $item->Price = isset($price->PriceClient) ? $price->PriceClient : 0;
                    $item->Available = isset($price->AvailableClient_MSK) ? $price->AvailableClient_MSK : 0;
                    break;
                endif;
            endforeach;


        endforeach;

        $this->tp->assign($a);
        $this->tp->parse( $this->tag, $this->mname . '/' . $this->mname . '.tpl');
    }

    public function item()
    {
        $model = $this->mname . '_model';
        $itemID = $this->uri->segment(3);



        // получаем данные товара
        $response = $this->$model->getItems(NULL, $itemID);

        $a['No'] = $response[0]->No;
        $a['name'] = $a['page_title'] = $response[0]->Name;
        $a['brand'] = $response[0]->Brand;
        $a['vendor_part'] = $response[0]->Vendor_part;
        $a['warranty'] = $response[0]->Warranty;

        $a['breadcrumbs'] = '<a href="/catalog/' . $response[0]->GroupCode1. '">' . $response[0]->GroupName1 . '</a>&nbsp;>&nbsp;' .
                            '<a href="/catalog/' . $response[0]->GroupCode2. '">' . $response[0]->GroupName2 . '</a>&nbsp;>&nbsp;' .
                            '<a href="/catalog/' . $response[0]->GroupCode3. '">' . $response[0]->GroupName3 . '</a>';

        // получаем характеристики товара
        $response = $this->$model->getItemsProperties($itemID);

        $a['properties'] = '<table><tbody>';
        foreach($response as $pr):
            $a['properties'] .= '<tr><td><strong>' . $pr->PropertyName . '</strong></td><td>' . $pr->Value . '</td></tr>';
        endforeach;
        $a['properties'] .= '</tbody></table>';

        // получаем изображения товара и формируем галерею
        $response = $this->$model->getItemsImages(NULL, $itemID);

        if( is_null($response[0]->FileName) ):
            $a['fotorama'] = '<img src="http://its-ci.ru/assets/upload/noimage.jpg" />';
        else:
            $a['fotorama'] = '';
            foreach($response as $key => $img):
                if( $img->ViewType == 'v' && $img->SizeType == 'm' )
                    $a['fotorama'] .= '<a href="http://img.merlion.ru/items/' . $img->FileName . '">';

                if( $img->ViewType == 'v' && $img->SizeType == 's' )
                    $a['fotorama'] .= '<img src="http://img.merlion.ru/items/' . $img->FileName . '"></a>';
            endforeach;
        endif;

        // получаем цены и доступность товара
        $price = $this->$model->getItemsAvail(NULL,$itemID)[0];
        $a['price'] = $price->PriceClient ? $price->PriceClient : 0;
        $a['available'] = $price->AvailableClient ? $price->AvailableClient : 0;

        $this->tp->assign($a);
        $this->tp->parse( $this->tag, $this->mname . '/' . $this->mname . '_item.tpl');
    }

    public function addToCart()
    {
        $data = array(
            'id'      => $this->input->post('item_id', TRUE),
            'qty'     => $this->input->post('quantity', TRUE),
            'price'   => $this->input->post('item_price', TRUE),
            'name'    => $this->input->post('item_name', TRUE),
            'options' => array()
        );

        $result['rowid'] = $this->cart->insert($data);

        if( !isset($result['rowid']) ):
            $result['rowid'] = -1;
        endif;

        exit(json_encode($result));
    }

    public function showMiniCart()
    {
        $a['cart_items_cnt'] = 0;
        $a['cart_sum_total'] = 0;

        $cart_items = $this->cart->contents();

        $a['cart_items_cnt'] = count($cart_items);
        $a['cart_sum_total'] = 0;

        foreach( $cart_items as $key => $item ):
            $a['cart_sum_total'] += $item['subtotal'];
        endforeach;

        $this->tp->assign($a);
        $this->tp->parse( 'minicart', $this->mname . '/' . $this->mname . '_minicart.tpl');

    }

    public function search()
    {
        $model = $this->mname . '_model';

        //session_start();

        if( $this->input->post('q', TRUE) )
            $_SESSION['q'] = $this->input->post('q', TRUE);

        $search = $_SESSION['q'];


        $items = $this->$model->getAllItems('Order');

        //var_dump_print($items);
        $arr = array();
        foreach( $items as $item ):
            if( strpos($item->Name, $search) !== FALSE):
                $arr[] = array(
                                    'item_url' => '/catalog/item/' . $item->No,
                                    'item_name' => $item->Name,
                                    'brand' => $item->Brand,
                                    'cat_path' => $item->GroupName1 . '/' . $item->GroupName2 . '/' . $item->GroupName3,
                                    'cat_url' => '/catalog/' . $item->GroupCode3
                                );
            endif;
        endforeach;

        $this->load->library('pagination');
        $config['base_url'] = base_url('catalog/search');
        $config['total_rows'] = count($arr);
        $config['per_page']   = 10;
        $config['num_links']  = 5;
        //$config['page_query_string'] = true;
        $this->pagination->initialize($config);

        // создаем пагинацию
        $a['search_pagination'] =  $this->pagination->create_links();

        // выбираем 10 товаров для отображения на странице
        $a['results'] = array_slice($arr, ( $this->uri->segment(3) ? $this->uri->segment(3) : 0), $config['per_page']);

        $this->tp->assign($a);
        $this->tp->parse( $this->tag, $this->mname . '/' . $this->mname . '_search_results.tpl');
    }

    /**
     * Функция загрузки файла
     * @param string $url Ссылка для загрузки файла
     * @param string $file Полный путь с названием сохраняемого файла
     */
    private static function _curl_download($url, $file)
    {


        // открываем файл, на сервере, на запись
        $dest_file = @fopen($file, "w");

        // открываем cURL-сессию
        $resource = curl_init();

        // устанавливаем опцию удаленного файла
        curl_setopt($resource, CURLOPT_URL, $url);

        // устанавливаем место на сервере, куда будет скопирован удаленной файл
        curl_setopt($resource, CURLOPT_FILE, $dest_file);

        // заголовки нам не нужны
        curl_setopt($resource, CURLOPT_HEADER, 0);

        // выполняем операцию
        curl_exec($resource);

        // закрываем cURL-сессию
        curl_close($resource);

        // закрываем файл
        fclose($dest_file);
    }



}
