<?php
/**
 * Created by PhpStorm.
 * User: supreme
 * Date: 27.04.14
 * Time: 3:06
 */

namespace wbl\boopups\widgets;

use app\components\web\Request;
use yii\helpers\Html;
use yii\helpers\Url;

class ToggledIFrameModal extends ToggledModal {

	/**
	 * Ссылка на ресурс iframe.
	 * @var string
	 */
	public $url = ['#'];

	/**
	 * @inheritdoc
	 */
	public $body = '<iframe src="{url}" class="modal-body-iframe"></iframe>';

	/**
	 * @inheritdoc
	 */
	public $footer = '';

	/**
	 * @inheritdoc
	 */
	protected $layoutOptions = [
		'class' => 'modal modal-iframe fade',
	];


	/**
	 * @inheritdoc
	 */
	public function renderBody() {
		// собираем url
		$url = Url::toRoute(array_merge($this->url, [
			'modal' => '#' . $this->layoutOptions['id'],
			'mode' => Request::MODE_MODAL
		]));

		$body = strtr($this->body, [
			'{url}' => $url,
		]);

		return Html::tag('div', $body, $this->bodyOptions);
	}
} 