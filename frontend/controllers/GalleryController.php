<?php

namespace frontend\controllers;
use Yii;
use backend\models\Galleryalbum;
use backend\models\GalleryalbumSearch;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

class GalleryController extends \yii\web\Controller
{
    private $tableGalleryImage = '{{%gallery_image}}';
    private $tableGalleryAlbum = '{{%gallery_album}}';
    private $tableSections = '{{%sections}}';

    const DATE_FORMAT = 'php:m/d/Y';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';

   public function actionIndex()
    {

        $get = Yii::$app->request->get();
        $where['album.active'] = 1;//[ => '1'];
        $show = 0;
        if(isset($get['show']) && $get['show'] > 0){
            $show = $get['show'];
        }
        $section = 0;
        if(isset($get['section']) && $get['section'] > 0){
            $section = $get['section'];
            $where['s.id'] = $section;
        }

        $mycheck=0;
        $date = $this->convert(date('Y-m-d H:i:s'));
        if(isset($get['date']) && $get['date']!=""){
            $date = $get['date'];
            $where['album.created'] = strtotime($this->convert($date, 'datetime'));
            $mycheck=1;
        }



        if(Yii::$app->language=='es-ES') $stitle='s.title_es as stitle';
        else $stitle='s.title as stitle';

        $myQuery = new \yii\db\Query();
        $myQuery->select(['s.id',$stitle])
            ->from($this->tableGalleryAlbum.' AS alb')
            ->leftJoin(['s'=>$this->tableSections],'alb.id_section=s.id')
            ->groupBy('s.id')
            ->all();
        $myCommand = $myQuery->createCommand();
        $myCmd = $myCommand->queryAll();


        $subQuery = new \yii\db\Query();
        $subQuery->select(['count(*) as cnt']);
        $subQuery->from($this->tableGalleryImage.' AS i');
        $subQuery->where('i.ownerId=album.id');
        $subQuery->groupBy['i.ownerId'];

        $subImage = new \yii\db\Query();
        $subImage->select(['img.id']);
        $subImage->from($this->tableGalleryImage.' AS img')
            ->where('img.ownerId=album.id')
            ->orderBy('img.rank asc')
            ->LIMIT(1);


        $query = new \yii\db\Query();
        $query->select(['album.id', 'album.title', 'album.created',$stitle,'cnt'=>$subQuery,'imgid'=>$subImage])
            ->from($this->tableGalleryAlbum.' AS album')
            ->leftJoin(['s' => $this->tableSections], 's.id=album.id_section')
            ->where($where)
            ->orderBy('created desc')
            ->all();
        $command = $query->createCommand();
        $cmd= $command->queryAll();

        $provider = new ArrayDataProvider([
            'allModels' => $cmd,
            'pagination' => [
                'pageSize' => 16,
            ],
        ]);
        $posts = $provider->getModels();
        $count = $provider->getCount();
        $totalCount = $provider->getTotalCount();
        $pagination = new Pagination(['totalCount' => $totalCount,'pageSize'=>16]);
        $galleryalbum = $posts;

        return $this->render('index',
            [
                'galleryalbum' => $galleryalbum,
                'pages'=>$pagination,
                'mysection'=> $myCmd,
                'section'=>$section,
                'date'=> $date,
                'lang'=>Yii::$app->language,
                'mycheck'=>$mycheck,
                'count'=>$count,
                'show'=>$show
            ]);
    }

    public static function convert($dateStr, $type='date', $format = null) {
        if ($type === 'datetime') {
            $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
            $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        else {
            $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    public  function  actionView($id){
        if(Yii::$app->language=='es-ES') $stitle='s.title_es as title';
        else $stitle='s.title as title';
        $myQuery = new \yii\db\Query();
        $myQuery->select(['alb.title as albtitle',$stitle,'alb.created'])
            ->from($this->tableGalleryAlbum.' AS alb')
            ->leftJoin(['s'=>$this->tableSections],'alb.id_section=s.id')
            ->where(['alb.id' => $id])
            ->one();
        $myCommand = $myQuery->createCommand();
        $myCmd = $myCommand->queryOne();


        $query = new \yii\db\Query();
        $query->select(['img.id as imgid', 'img.type', 'img.ownerId as id', 'img.rank', 'img.name', 'img.description','s.title','s.title_es','alb.created'])
            ->from($this->tableGalleryImage.' AS img')
            ->leftJoin(['alb'=>$this->tableGalleryAlbum],'alb.id=img.ownerId')
            ->leftJoin(['s'=>$this->tableSections],'alb.id_section=s.id')
            ->where(['img.ownerId' => $id])
            ->orderBy('img.rank asc')
            ->all();
        $command = $query->createCommand();
        $cmd = $command->queryAll();

        $provider = new ArrayDataProvider([
            'allModels' => $cmd,
            'pagination' => [
                'pageSize' => 16,
            ],
        ]);
        $posts = $provider->getModels();
        // $count = $provider->getCount();
        $totalCount = $provider->getTotalCount();
        $pagination = new Pagination(['totalCount' => $totalCount,'pageSize'=>16]);
        $galleryalbum = $posts;
        return $this->render('view', [
            'galleryalbum' => $galleryalbum,
            'pages'=>$pagination,
            'myalbum'=>$myCmd
        ]);
    }

}
