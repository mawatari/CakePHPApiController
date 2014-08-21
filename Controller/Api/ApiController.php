<?php
App::uses('AppController', 'Controller');

/**
 * Api Controller
 *
 */
class ApiController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = ['RequestHandler'];

/**
 * Result
 *
 * @var array
 */
	public $result = [];

/**
 * beforeFilter method
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->request->is('ajax')) throw new BadRequestException('Ajax以外でのアクセスは許可されていません。');
		$this->response->header('X-Content-Type-Options', 'nosniff');

		// コントローラー名から'Api'文字列を除去し、モデル名として利用する
		$this->uses = $this->modelClass = Inflector::singularize(preg_replace('|^Api|', '', $this->name));

		// 数値型等を正しくえる為、PDOオプションを変更
		$pdo = $this->{$this->modelClass}->getDatasource()->getConnection();
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		// 全てのレスポンスで共通のメタデータをセット
		$this->result['meta'] = [
			'url' => $this->request->here,
			'method' => $this->request->method(),
		];
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$response = $this->{$this->modelClass}->find('all');
		$this->success($response);
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->{$this->modelClass}->exists($id)) {
			return $this->error('指定IDのデータが存在しません。');
		}
		$response = $this->{$this->modelClass}->findById($id);
		$this->success($response);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->{$this->modelClass}->create();
		$result = $this->{$this->modelClass}->save($this->request->data);
		if ($result) {
			$this->success($result);
		} else {
			$this->validationError($this->modelClass, $this->{$this->modelClass}->validationErrors);
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->{$this->modelClass}->id = $id;
		$result = $this->{$this->modelClass}->save($this->request->data);
		if ($result) {
			$this->success($result);
		} else {
			$this->validationError($this->modelClass, $this->{$this->modelClass}->validationErrors);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if ($this->{$this->modelClass}->delete($id)) {
			$this->success(['message' => 'データの削除に成功しました。']);
		} else {
			$this->error('データの削除に失敗しました');
		}
	}

/**
 * success method
 * 成功としてレスポンスする
 *
 * @param array $response
 */
	protected function success($response = []) {
		$this->result['response'] = $response;

		$this->set([
			'meta'       => $this->jsonEncode($this->result['meta']),
			'response'   => $this->jsonEncode($this->result['response']),
			'_serialize' => ['meta', 'response']
		]);
	}

/**
 * error method
 * エラーとしてレスポンスする
 *
 * @param string $message
 * @param integer $code
 */
	protected function error($message = '', $code) {
		$this->result['error']['message'] = $message;
		$this->result['error']['code'] = $code;

		$this->response->statusCode(400);
		$this->set([
			'meta'       => $this->jsonEncode($this->result['meta']),
			'error'      => $this->jsonEncode($this->result['error']),
			'_serialize' => ['meta', 'error']
		]);
	}

/**
 * validation error method
 * バリデーションエラーとしてレスポンスする
 *
 * @param string $modelName
 * @param array $validationError
 */
	protected function validationError($modelName, $validationError = []) {
		$this->result['error']['message'] = 'Validation Error';
		$this->result['error']['code'] = '422';
		$this->result['error']['validation'][$modelName] = [];
		foreach ($validationError as $key => $value) {
			$this->result['error']['validation'][$modelName][$key] = $value[0];
		}

		$this->response->statusCode(400);
		$this->set([
			'meta'       => $this->jsonEncode($this->result['meta']),
			'error'      => $this->jsonEncode($this->result['error']),
			'_serialize' => ['meta', 'error']
		]);
	}

/**
 * json encode method
 *
 * @param array $data
 */
	protected function jsonEncode($data = []) {
		return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	}
}
