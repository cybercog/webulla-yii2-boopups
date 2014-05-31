<?php
/**
 * Created by PhpStorm.
 * User: supreme
 * Date: 27.04.14
 * Time: 1:48
 */

namespace wbl\boopups\widgets;

use Yii;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Resizable;

class ToggledModal extends BaseWidget {

	/**
	 * Текст кнопки.
	 * @var string
	 */
	public $label = 'Label';

	/**
	 * Ссылка для кнопки.
	 * @var string
	 */
	public $url = '#';

	/**
	 * @inheritdoc
	 */
	protected $buttonOptions = [
		'class' => 'btn btn-success',
	];


	/**
	 * Заголовок окна.
	 * @var string
	 */
	public $title = 'Title';

	/**
	 * Шаблон модального окна.
	 * @var string
	 */
	public $layout = '
		<div tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" {layoutOptions}>
			<div {dialogOptions}>
				<div {contentOptions}>
					{header}
					{body}
					{footer}
				</div>
			</div>
		</div>';

	/**
	 * @var string
	 */
	public $header = '
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{title}</h4>';

	/**
	 * @var string
	 */
	public $body;

	/**
	 * @var string
	 */
	public $footer = '{buttons}';

	/**
	 * @var array
	 */
	protected $layoutOptions = [
		'class' => 'modal fade',
	];

	/**
	 * @var array
	 */
	protected $dialogOptions = [
		'class' => 'modal-dialog',
	];

	/**
	 * @var array
	 */
	protected $contentOptions = [
		'class' => 'modal-content',
	];

	/**
	 * @var array
	 */
	protected $headerOptions = [
		'class' => 'modal-header',
	];

	/**
	 * @var array
	 */
	protected $bodyOptions = [
		'class' => 'modal-body',
	];

	/**
	 * @var array
	 */
	protected $footerOptions = [
		'class' => '',
	];

	/**
	 * @var array
	 */
	protected $buttonsOptions = [
		'cancel' => [
			'label' => 'close',
			'options' => [
				'class' => 'btn btn-default',
				'data' => [
					'dismiss' => 'modal'
				]
			]
		]
	];


	/**
	 * @inheritdoc
	 */
	public function __set($name, $value) {
		// если переданы опции
		if(strpos($name, 'Options')) {
			$this->$name = array_merge($this->$name, $value);
		} else {
			parent::__set($name, $value);
		}
	}


	/**
	 * @return string
	 */
	public function buildUrl() {
		return Url::toRoute(array_merge($this->url, [
			'mode' => 'popup'
		]));
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
	 * @inheritdoc
	 */
	public function run() {
		// генерируем id кнопки
		$this->buttonOptions['id'] = 'btn-' . $this->getId();

		// генерируем id окна
		$this->layoutOptions['id'] = 'modal-' . $this->getId();

		// регистрируем скрипт инициализации popup
		$this->view->registerJs('(function() {' . $this->renderScript() . '})();');

		Resizable::widget();


		// компилируем кнопку
		return $this->renderButton();
	}

	/**
	 * @return string
	 */
	public function renderButton() {
		return Html::a($this->label, Url::toRoute($this->url), $this->buttonOptions);
	}

	/**
	 * @return string
	 */
	public function renderScript() {
		return '
			var $button = $(' . json_encode('#' . $this->buttonOptions['id']) . '),
				$modal = $(' . json_encode($this->renderLayout()) . ');

			$button.click(function(e) {
				e.preventDefault();
				$modal = $(' . json_encode($this->renderLayout()) . ').modal();

				$(".modal-dialog", $modal).resizable({
					alsoResize: ".modal-body",
					maxWidth: $(window).width() - 200,
					maxHeight: $(window).height() - 200,
				});


				$modal.on("hidden.bs.modal", function() {
					$modal.remove();
				});
			});';
	}

	/**
	 * @return string
	 */
	public function renderLayout() {
		return strtr($this->layout, [
			'{layoutOptions}' => Html::renderTagAttributes($this->layoutOptions),
			'{dialogOptions}' => Html::renderTagAttributes($this->dialogOptions),
			'{contentOptions}' => Html::renderTagAttributes($this->contentOptions),
			'{headerOptions}' => Html::renderTagAttributes($this->headerOptions),
			'{bodyOptions}' => Html::renderTagAttributes($this->bodyOptions),
			'{footerOptions}' => Html::renderTagAttributes($this->footerOptions),

			'{header}' => $this->renderHeader(),
			'{body}' => $this->renderBody(),
			'{footer}' => $this->renderFooter()
		]);
	}

	/**
	 * @return string
	 */
	public function renderHeader() {
		$header = strtr($this->header, [
			'{label}' => $this->label,
			'{title}' => $this->title,
		]);

		return $header ? Html::tag('div', $header, $this->headerOptions) : null;
	}

	/**
	 * @return string
	 */
	public function renderBody() {
		return $this->body ? Html::tag('div', $this->body, $this->bodyOptions) : null;
	}

	/**
	 * @return string
	 */
	public function renderFooter() {
		$buttons = '';
		foreach($this->buttonsOptions as $button) {
			$button && ($buttons .= Html::a($button['label'], '#', $button['options']));
		}

		$footer = strtr($this->footer, [
			'{buttons}' => $buttons,
		]);

		return $footer ? Html::tag('div', $footer, $this->footerOptions) : null;
	}
}