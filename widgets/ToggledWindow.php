<?php
/**
 * Created by PhpStorm.
 * User: supreme
 * Date: 27.04.14
 * Time: 5:47
 */

namespace wbl\boopups\widgets;

use app\components\web\Request;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ToggledWindow extends Widget {

	/**
	 * Текст кнопки.
	 * @var string
	 */
	public $label = 'Label';

	/**
	 * Ссылка кнопки.
	 * @var string
	 */
	public $url = '#';

	/**
	 * Опции кнопки.
	 * @var array
	 */
	public $buttonOptions = [
		'class' => 'btn btn-success',
	];

	/**
	 * Заголовок окна.
	 * @var string
	 */
	public $title = 'Title';

	/**
	 * Опции окна.
	 * @var array
	 */
	public $windowOptions = [
		'menubar' => 'yes',
		'location' => 'yes',
		'resizable' => 'yes',
		'scrollbars' => 'yes',
		'status' => 'yes',
		'width' => 600,
		'height' => 400,
	];


	/**
	 * @return string
	 */
	public function buildUrl() {
		return Url::toRoute($this->url);
	}

	/**
	 * @return array
	 */
	public function buildButtonOptions() {
		return array_merge($this->buttonOptions, [
			'id' => 'btn-' . rand(1, 1000)
		]);
	}

	/**
	 * @return string
	 */
	public function buildSrc() {
		return Url::toRoute(array_merge($this->url, [
			'mode' => Request::MODE_POPUP
		]));
	}

	/**
	 * @return string
	 */
	public function buildWindowOptions() {
		$options = [];
		foreach($this->windowOptions as $key => $val) {
			$options[] = $key . '=' . $val;
		}

		return implode(',', $options);
	}


	/**
	 * @inheritdoc
	 */
	public function run() {
		// генерируем id кнопки
		$this->buttonOptions['id'] = 'btn-' . rand(1, 1000);

		// регистрируем скрипт инициализации popup
		$this->view->registerJs('(function() {' . $this->renderScript() . '})();');

		// компилируем кнопку
		return $this->renderButton();
	}

	/**
	 * @return string
	 */
	public function renderButton() {
		return Html::a($this->label, $this->buildUrl(), $this->buttonOptions);
	}

	/**
	 * @return string
	 */
	public function renderScript() {
		return '
			$(' . json_encode('#' . $this->buttonOptions['id']) . ').click(function(e) {
				e.preventDefault();

				window.open(' . json_encode($this->buildSrc()) . ', ' . json_encode($this->title) . ', ' . json_encode($this->buildWindowOptions()) . ');
			});';
	}
} 