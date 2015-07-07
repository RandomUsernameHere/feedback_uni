<?php

    (defined('BASEPATH')) OR exit('No direct script access allowed');

    /**
     * Image CMS
     * Module Feedback_uni
     */
    class Feedback_Uni extends MY_Controller {

        /** Подготовим необходимые свойства для класса */
        /*Пока не ясно, нужны ли эти свойства*/
        protected $key = FALSE;
        protected $mailTo = FALSE;


        public function __construct() {
            parent::__construct();
            $lang = new MY_Lang();
            $lang->load('feedback_uni');
            $this->load->module('core');

            /** Запускаем инициализацию переменых. Значения будут взяты з
             *  Базы Данных, и присвоены соответствующим переменным */
            $this->initSettings();
        }

        public function index() {
            $this->core->error_404();
        }

        /**
         * Метод относится к стандартным методам ImageCMS.
         * Будет вызван каждый раз при обращении к сайту.
         * Запускается при условии включении "Автозагрузка модуля-> Да" в панели
         * уплавнеия модулями.
         */
        public function autoload() {
            /*if ('TRUE' == $this->useEmailNotification)*/
            //  \CMSFactory\Events::create()->setListener('handler', 'Feedback_Uni:__construct');

            /*Сомневаюсь, что это нужно.
            Если судить по названию, то это просто прослушка нового комментария*/
            /*\CMSFactory\Events::create()->setListener('handler', 'Commentsapi:newPost');*/
        }


        /*Насколько я понимаю, эта функция меняет статус комментария. Она не нужна*/
//        public function changeStatus($commentId, $status, $key) {
//            /** Проверим входные данные */
//            ($commentId AND in_array($status, array(0, 1, 2)) AND $key == $this->key) OR $this->core->error_404();
//
//            /** Обновим статус */
//            $this->db
//                ->where('id', intval($commentId))
//                ->set('status', intval($status))
//                ->update('comments');
//
//            $comment = $this->db->where('id', $commentId)->get('comments')->row();
//            if ($comment->module == 'core')
//                /** Используем помощник get_page($id) который аргументом принимает ID страницы.
//                 *  Помощник включен по умолчанию. Больше о функция помощника
//                 *  читайте здесь http://ellislab.com/codeigniter/user-guide/general/helpers.html */
//                $comment->source = get_page($comment->item_id);
//
//
//            /** Сообщаем пользователю что статус обновлён успешно */
//            \CMSFactory\assetManager::create()
//                ->setData('comment', $comment)
//                ->render('successful');
//        }


        /**
         * Метод обработчик
         * @param $param array
         * type $commentId <p>ID коментария который был только что создан.</p>
         */
        public static function handler(array $param) {
            $instance = new Feedback_Uni();
            $instance->composeAndSendEmail($param);
        }

        //TODO: переделать эту функцию
        protected function composeAndSendEmail($arg) {
//            $comment = $this->db->where('id', $arg['commentId'])->get('comments')->row();
//            if ($comment->module == 'core')
//                /** Используем помощник get_page($id) который аргументом принимает ID страницы.
//                 *  Помощник включен по умолчанию. Больше о функция помощника
//                 *  читайте здесь http://ellislab.com/codeigniter/user-guide/general/helpers.html */
//                $comment->source = get_page($comment->item_id);
//
//            /** Теперь переменная содержит HTML тело нашего письма */
//            $message = \CMSFactory\assetManager::create()->setData(array('comment' => $comment, 'key' => $this->key))->fetchTemplate('emailPattern');
//
//            /** Настроявием отправку Email http://ellislab.com/codeigniter/user-guide/libraries/email.html */
//            $this->load->library('email');
//            $this->email->initialize(array('mailtype' => 'html'));
//            $this->email->from('robot@sitename.com', 'Comments Robot');
//            $this->email->to($this->mailTo);
//            $this->email->subject('New Comment received');
//            $this->email->message($message);
//            $this->email->send();
            //        echo $this->email->print_debugger();
        }

        private function initSettings() {
            $request = $this->db->get('mod_feedback_uni_settings');
            //TODO: тут переделать. Нужно каким-то образом передавать id записи для из виджета
            /*if ($request) {
                $DBSettings = $request->result_array();
                foreach ($DBSettings as $record)
                    $this->$record['name'] = $record['value'];
            }*/
        }

        /**
         * Метод относиться  к стандартным методам ImageCMS.
         * Будет вызван при установке модуля пользователем
         */
        public function _install() {
            /** Подключаем класс Database Forge содержащий функции,
             *  которые помогут вам управлять базой данных.
             *  http://ellislab.com/codeigniter/user-guide/database/forge.html */
            $this->load->dbforge();

            /** Создаем массив полей и их атрибутов для БД */
            $fields = array
            (
                'id'=>array
                (//id записи
                    'type'=>'INT',
                    'constraint'=>10,
                    'unsigned'=>true,
                    'auto_increment'=>true
                ),
                'mail_to'=>array
                (//на этот адрес отправляем письма
                    'type'=>'VARCHAR',
                    'constraint'=>256,
                ),
                'mail_from'=>array
                (//Типа с этого адреса они приходят
                    'type'=>'VARCHAR',
                    'constraint'=>256,
                    'null'=>true
                ),
                'theme'=>array
                (//Тема письма
                    'type'=>'VARCHAR',
                    'constraint'=>256,
                    'null'=>true
                ),
                'text'=>array
                (//Текст письма
                    'type'=>'TEXT'
                ),
                'is_html'=>array
                (//Формат писем простой текст или html
                    'type'=>'INT',
                    'constraint'=>1,
                    'unsigned'=>true,
                    'default'=>0
                ),
                'data_set_name'=>array
                (
                    'type'=>'VARCHAR',
                    'constraint'=>256,
                )
            );

            /** Указываем на поле, которое будет с ключом Primary */
            $this->dbforge->add_key('id', TRUE);
            /** Добавим поля в таблицу */
            $this->dbforge->add_field($fields);
            /** Запускаем запрос к базе данных на создание таблицы */
            $this->dbforge->create_table('mod_feedback_uni_settings', TRUE);

            /** Заполним поля таблицы временными данными */
            /** ...и добавим их в Базу Данных */
            $this->db->simple_query("
                INSERT INTO `mod_feedback_uni_settings` (`mail_to`, `text`, `data_set_name`)
                VALUES ('admin@admin.com','Test message','Default data set')
            ");

            /** Обновим метаданные модуля, включим автозагрузку модуля и доступ по URL */
            $this->db->where('name', 'feedback_uni')
                ->update('components', array('autoload' => '1', 'enabled' => '0'));
        }

        /**
         * Метод относиться  к стандартным методам ImageCMS.
         * Будет вызван при удалении модуля пользователем
         */
        public function _deinstall() {
            $this->load->dbforge();
            $this->dbforge->drop_table('mod_feedback_uni_settings');
        }

    }

    /* End of file feedback_uni.php */
