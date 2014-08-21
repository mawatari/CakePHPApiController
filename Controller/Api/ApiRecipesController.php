<?php
App::uses('ApiController', 'Controller');

/**
 * Api Recipes Controller
 *
 * @property Recipe $Recipe
 */
class ApiRecipesController extends ApiController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$response = [
			[
				'id'           => 1,
				'title'        => 'クッキー',
				'description'  => '表面はサクサク、中はしっとりのチョコレートクッキー',
				'ingredients'  => [
					[
						'amount'         => 1,
						'amountUnits'    => '袋',
						'ingredientName' => 'チップスアホイ'
					]
				],
				'instructions' => '1. コンビニでチョコレートクッキーを買う。'
			],
			[
				'id'           => 2,
				'title'        => 'ビスケット',
				'description'  => '中までサクサクのビスケット',
				'ingredients'  => [
					[
						'amount'         => 1,
						'amountUnits'    => '袋',
						'ingredientName' => 'ビスケット'
					]
				],
				'instructions' => '1. コンビニでビスケットを買う。'
			],
		];
		$this->success($response);
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$response = [
			'id'           => 1,
			'title'        => 'クッキー',
			'description'  => '表面はサクサク、中はしっとりのチョコレートクッキー',
			'ingredients'  => [
				[
					'amount'         => 1,
					'amountUnits'    => '袋',
					'ingredientName' => 'チップスアホイ'
				]
			],
			'instructions' => '1. コンビニでチョコレートクッキーを買う。'
		];
		$this->success($response);
	}
}
