<?php

class GetVendors
{
	const limit = 300; // максимальное кол-во результатов в выдаче
	public $main_acf;
	private $args;
	private $users;
	private $id;
	public function __construct($id, $membership = null, $btns)
	{
		$this->id = $id;
		$this->btn = array();
		$this->btn['all_perspectives'] = $btns[0];
		$this->btn['print'] = $btns[1];
		$this->membership = $membership;
		$this->main_acf = [
			'field0' => array(
				'fields' => array(
					'name1' => 'Gruppy_produkczii_legproma',
					//'name1' => 'Gruppy_legproma',
					'name2' => 'Napravleniyaraboty',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
				'tz_empty' => [0 => 'Пошив одежды'],
				// если тз не задано
			),
			'field1' => array(
				'fields' => array(
					'name1' => 'Tipodezhdy_lead',
					'name2' => 'Tipodezhdy',
				),
				'exists' => 1,
				'noexists' => 'exit',
				'type' => 'taxonomy',
			),
			'field2' => array(
				'fields' => array(
					'name1' => 'sfera_primeneniya_lead',
					'name2' => 'sfera_primeneniya',
					//'key2' => 'field_61e02fb11a939', // имеет приоритет над name2
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			// 'field3'  => array(
			//	 'fields'	  => array(
			//		 'name1' => 'vidy_izdelij',
			//		 'name2' => 'vidy_izdelie_update',
			//	 ),
			//	 'exists'	=> 1,
			//	 //'noexists'  => 'exit',
			//	 'noexists'  => 0,
			//	 'type'	  => 'taxonomy',
			// ),
			'field4' => array(
				'fields' => array(
					'name1' => 'kolichestvo_izdelij_order',
					'name2' => 'minimalnaya_partiya_sht_копия',
				),
				'tz_empty' => 100,
				// если тз не задано
				'exists' => array(
					'less_or_equals' => array(
						'value' => 1,
					),
					'more' => array(
						'exit' => 30,
						'symbol' => 'less',
						'value' => 1,
					),
				),
				'noexists' => 0,
				'type' => 'number',
			),
			'field5' => array(
				'fields' => array(
					'name1' => 'planovyj_byudzhet_order',
					'name2' => 'minimalnaya_partiya_rub_user',
				),
				'tz_empty' => 50000,
				// если тз не задано
				'exists' => array(
					'less_or_equals' => array(
						'value' => 1,
					),
					'more' => array(
						'exit' => 30,
						'symbol' => 'less',
						'value' => 1,
					),
				),
				'noexists' => 0,
				'type' => 'number',
			),
			'field6' => array(
				'fields' => array(
					'name1' => 'pol_vozrast',
					'name2' => 'spec_update',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field7' => array(
				'fields' => array(
					'name1' => 'sezon',
					'name2' => 'po_sezonu',
				),
				'exists' => 1,
				//'noexists'  => 'exit',
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field8' => array(
				'fields' => array(
					'name1' => 'region_dostavki',
					'name2' => array(
						'factory' => 'Regionproizvodstva_user',
						'delivery' => 'region'
					),
				),
				'exists' => array(
					'factory' => 4,
					'delivery' => 3,
					'federation' => 2,
				),
				'noexists' => 0,
				'type' => 'region',
			),
			'field9' => array(
				'fields' => array(
					'name1' => 'usloviya_oplaty',
					'name2' => 'otsrochka_platezha_user',
				),
				'exists' => 2,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field10' => array(
				'fields' => array(
					'name1' => 'usloviya_dostavki',
					'name2' => 'varianty_dostavki',
				),
				'exists' => 2,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field11' => array(
				'fields' => array(
					'name1' => 'upakovka',
					'name2' => 'varianty_upakovki',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field12' => array(
				'fields' => array(
					'name1' => 'czenovoj_segment_order',
					'name2' => 'czenovye_segmenty',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field13' => array(
				'fields' => array(
					'name1' => 'Verhnyayazimnyayaodezhda',
					'name2' => 'Verhnyayazimnyayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field14' => array(
				'fields' => array(
					'name1' => 'Verhnyayademisezonnayaodezhda',
					'name2' => 'Verhnyayademisezonnayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field15' => array(
				'fields' => array(
					'name1' => 'vyazanaya_odezhda',
					'name2' => 'Vyazanayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field16' => array(
				'fields' => array(
					'name1' => 'Trikotazhnayaodezhda',
					'name2' => 'Trikotazhnayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field17' => array(
				'fields' => array(
					'name1' => 'Trikotazhnayaodezhda',
					'name2' => 'Trikotazhnayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field18' => array(
				'fields' => array(
					'name1' => 'Dzhinsovayaodezhda',
					'name2' => 'Dzhinsovayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field19' => array(
				'fields' => array(
					'name1' => 'izdeliya_kostyumnye',
					'name2' => 'izdeliya_kostyumnye',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field20' => array(
				'fields' => array(
					'name1' => 'legkaya_odezhda',
					'name2' => 'Legkayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field21' => array(
				'fields' => array(
					'name1' => 'domashnyaya_odezhda',
					'name2' => 'Domashnyayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field22' => array(
				'fields' => array(
					'name1' => 'nizhnee_bele',
					'name2' => 'nizhnee_bele',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field23' => array(
				'fields' => array(
					'name1' => 'Formennayaodezhda',
					'name2' => 'formennaya_odezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field24' => array(
				'fields' => array(
					'name1' => 'UniformadlyaHoReCa',
					'name2' => 'UniformadlyaHoReCa',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field25' => array(
				'fields' => array(
					'name1' => 'uniforma_dlya_sfery_uslug',
					'name2' => 'uniforma_dlya_sfery_uslug',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field26' => array(
				'fields' => array(
					'name1' => 'mediczinskaya_odezhda',
					'name2' => 'Mediczinskayaodezhda',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field27' => array(
				'fields' => array(
					'name1' => 'Pishhevoeproizvodstvo',
					'name2' => 'pishhevoe_proizvodstvo',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field28' => array(
				'fields' => array(
					'name1' => 'reklamnaya_industriya',
					'name2' => 'reklamnaya_industriya',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field29' => array(
				'fields' => array(
					'name1' => 'obrazovatelnye_uchrezhdeniya',
					'name2' => 'obrazovatelnye_uchrezhdeniya',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field30' => array(
				'fields' => array(
					'name1' => 'sportivnaya_odezhda_po_vidam_sporta',
					'name2' => 'sportivnaya_odezhda_po_vidam_sporta',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field31' => array(
				'fields' => array(
					'name1' => 'dlya_novorozhdennyh',
					'name2' => 'dlya_novorozhdennyh',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field32' => array(
				'fields' => array(
					'name1' => 'siz',
					'name2' => 'SIZ',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field33' => array(
				'fields' => array(
					'name1' => 'obuv',
					'name2' => 'obuv',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field34' => array(
				'fields' => array(
					'name1' => 'sumki',
					'name2' => 'Sumki',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field35' => array(
				'fields' => array(
					'name1' => 'platochno-sharfovye_izdeliya',
					'name2' => 'Platochno-sharfovyeizdeliya',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field36' => array(
				'fields' => array(
					'name1' => 'perchatki_i_zashhita_ruk',
					'name2' => 'Perchatkiizashhitaruk',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field37' => array(
				'fields' => array(
					'name1' => 'chulochno-nosochnye_izdeliya',
					'name2' => 'CHulochno-nosochnyeizdeliya',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field38' => array(
				'fields' => array(
					'name1' => 'aksessuary',
					'name2' => 'aksessuary',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field39' => array(
				'fields' => array(
					'name1' => 'tekstil_dlya_doma_i_biznesa',
					'name2' => 'tekstil',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field40' => array(
				'fields' => array(
					'name1' => 'Dizajnimodelirovanie',
					'name2' => 'dizajn_i_modelirovanie',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field41' => array(
				'fields' => array(
					'name1' => 'konstruirovanie',
					'name2' => 'konstruirovanie',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field42' => array(
				'fields' => array(
					'name1' => 'tehnologiya',
					'name2' => 'tehnologiya',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field43' => array(
				'fields' => array(
					'name1' => 'modelirovanie_izdeliya',
					'name2' => 'dizajn',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field44' => array(
				'fields' => array(
					'name1' => 'materialy_i_tehnologiya',
					'name2' => 'dizajn_копия',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
			'field45' => array(
				'fields' => array(
					'name1' => 'kontsruirovanie',
					'name2' => 'KONSTRUIROVANIE',
				),
				'exists' => 1,
				'noexists' => 0,
				'type' => 'taxonomy',
			),
		];
	}

	private function getMails()
	{
		return get_user_meta(get_current_user_id(), 'tz_mails_' . $this->id, true) ?? 'empty';
		if (!isset($this->ids)) {
			$keys = get_user_meta(get_current_user_id(), 'mails_' . $this->id, true);
			$ids = array();
			if (is_array($keys) && count($keys) > 0) {
				foreach ($keys as $clear_keys) {
					//$user_meta = get_user_meta(get_current_user_id(), $clear_key);
					if (is_array($clear_keys)) {
						foreach ($user_meta as $met) {
							$ids[] = $met;
						}
					} else {
						$ids[] = $clear_keys;
					}
				}
				if (count($ids) > 0) {
					$this->ids = $ids;
					return $this->ids;
				} else {
					$this->ids = 'empty';
					return $this->ids;
				}
			} else {
				$this->ids = 'empty';
				return $this->ids;
			}
		} else {
			return $this->ids;
		}
	}

	private function args_complete()
	{
		if ($this->membership_plan() == 'member' || !is_array($this->getMails()) || count($this->getMails()) < 3) {
			$args = array(
				'role' => 'wcfm_vendor',
				'fields' => 'ID',
				'number' => -1,
				//'number'		=> 1000,
				//'meta_key'		=> 'Tipodezhdy',
				'meta_compare' => 'IN',
			);
		} else {
			$args = array(
				'role' => 'wcfm_vendor',
				'fields' => 'ID',
				'number' => -1,
				//'meta_key'		=> 'Tipodezhdy',
				'meta_compare' => 'IN',
				'include' => $this->getMails(),
			);
		}
		return $args;
	}
	public function have_posts()
	{
		$users = get_users($this->args_complete());
		if (count($users) > 0) {
			$this->users = $users;
			return true;
		} else {
			return false;
		}
	}

	private function get_interseract($arr1, $arr2)
	{
		$tz_acf_ids = array();
		$user_acf_ids = array();
		if (is_array($arr1) && is_array($arr2)) {
			foreach ($arr1 as $tz_acf_key => $tz_acf_value) {
				if (is_object($tz_acf_value)) {
					$tz_acf_ids[] = $tz_acf_value->term_id;
				} else {
					$tz_acf_ids[] = $tz_acf_value;
				}
			}
			foreach ($arr2 as $user_acf_key => $user_acf_value) {
				if (is_object($user_acf_value)) {
					$user_acf_ids[] = $user_acf_value->term_id;
				} else {
					$user_acf_ids[] = $user_acf_value;
				}
			}
			if (count(array_intersect($tz_acf_ids, $user_acf_ids)) == 0) {
				$interseract = 0;
			} else {
				$interseract_count = count(array_intersect($tz_acf_ids, $user_acf_ids));
				if ($interseract_count == count($user_acf_ids) && $interseract_count == count($tz_acf_ids)) {
					$interseract = 2;
				} else {
					$interseract = 1;
				}
			}
			return $interseract;
		} else {
			$interseract = 0;
			return $interseract;
		}
	}

	private function getRegion($tz_acf, $user_factory, $user_delivery, $value, $federation_ids)
	{
		$tz_regions = array();
		$user_factories = $user_factory;
		$user_deliveries = array();
		if (is_array($tz_acf) && (is_array($user_factories) || is_array($user_delivery))) {
			foreach ($tz_acf as $object_tz) {
				$tz_regions[] = $object_tz->term_id;
			}
			if (is_array($user_delivery)) {
				foreach ($user_delivery as $object_delivery) {
					$user_deliveries[] = $object_delivery->term_id;
				}
			}
			if ((is_array($user_factories) && array_intersect($tz_regions, $user_factories)) || $tz_regions[0] == $user_factories[1]) {
				return $value['exists']['factory'];
			} else if (array_intersect($tz_regions, $user_deliveries) || $tz_regions[0] == $user_deliveries[1]) {
				return $value['exists']['delivery'];
			} else {
				foreach ($federation_ids as $federation) {
					foreach ($federation['federation_ids'] as $federation_id) {
						foreach ($user_deliveries as $delivery) {
							if ($delivery == $federation_id) {
								$fed_del = $federation_id;
								break 3;
							} else {
								continue;
							}
						}
					}
				}
				if (isset($fed_del)) {
					foreach ($federation_ids as $federation) {
						foreach ($federation['federation_ids'] as $federation_id) {
							foreach ($user_deliveries as $regions) {
								if ($federation_id == $regions) {
									$fed_reg = $federation_id;
								} else {
									continue;
								}
							}
						}
					}
				}
				if (isset($fed_del) && isset($fed_reg) && $fed_reg == $fed_del) {
					return $value['exists']['federation'];
				} else {
					return $value['noexists'];
				}
			}
		} else {
			return $value['noexists'];
		}
	}

	private function getFederationRegion()
	{
		$terms = get_terms('lp_912_federalnii_okrug', array('hide_empty' => false, 'hierarchical' => true, 'get' => 'all', 'number' => '100', ));
		$fullmap = array();
		foreach ($terms as $key => $term) {
			$fullmap[$key]['federation'] = $term->term_id;
			$federation_ids = get_term_meta($term->term_id, 'fo_and_region');
			foreach ($federation_ids as $value) {
				if (is_object($value)) {
					$fullmap[$key]['federation_ids'][] = $value->term_id;
				} else if (is_array($value)) {
					$fullmap[$key]['federation_ids'][] = $value['term_id'];
				} else {
					$fullmap[$key]['federation_ids'][] = $value;
				}
			}
		}
		return $fullmap;
	}

	private function GetClearUsers($federation_ids, $federation_assoc)
	{
		$users = $this->users;
		$clear_users = array();

		// Разборка пользователей
		foreach ($users as $user_key => $user_id) {
			$clear_users[$user_key]['userid'] = $user_id;
			$clear_users[$user_key]['match'] = 0;

			// Разборка полей
			foreach ($this->main_acf as $key => $value) {
				// первое поле от ТЗ
				$tz_field = $value['fields']['name1'];
				// второе поле от пользователя
				$tz_acf = get_field($tz_field);
				//print_r($key);

				//var_dump($tz_acf);//***array(4) { [0]=> int(14657) [1]=> int(14658) [2]=> int(14659) [3]=> int(14660) }
//var_dump($value['fields']);
//echo '<br/>***';

				// если поле тз пустое, то подходит любое значение
				if (!$tz_acf) {
					if (isset($value['tz_empty']))
						$tz_acf = $value['tz_empty'];
					else
						continue;
				}

				// инициализация
				$user_field = $value['fields']['name2'];
				if (is_array($user_field)) {
					// для региона доставки name2 - массив factory, delivery
					$user_acf = [];
					foreach ($user_field as $name => $field) {
						$user_acf[$name] = get_field($field, 'user_' . $user_id);
						if (!$user_acf[$name])
							$user_acf[$name] = [];
						if (!is_array($user_acf[$name]))
							$user_acf[$name] = [$user_acf[$name]];
					}
				} else {
					// для остальных полей
					$user_acf = get_field($user_field, 'user_' . $user_id);
				}

				if ($value['type'] == 'number') {
					// инициализация для числовых полей
					if (!$user_acf)
						$user_acf = 0;
					settype($user_acf, 'int');
					settype($tz_acf, 'int');
				} else if ($value['type'] == 'taxonomy') {

					if (is_array($tz_acf)) {

						$terms = array();

						foreach ($tz_acf as $key => $val) {

							$terms[] = get_term($val)->name;

						}

						$tz_acf = $terms;

					}



					if (!is_array($tz_acf))
						$tz_acf = [$tz_acf];
					if (!$user_acf)
						$user_acf = [];
					if (!is_array($user_acf))
						$user_acf = [$user_acf];
					// проверить, что в форме - id или названия
					if ($this->is_taxonomy_ids($user_acf)) {
						// получить названия вместо id
						$this->taxonomy($user_acf);
					}
					if ($this->is_taxonomy_ids($tz_acf)) {
						// получить названия вместо id
						$this->taxonomy($tz_acf);
					}
				}

				// сравнение значений тз и анкеты ($tz_acf не пустое)
				if ($value['type'] == 'region') {
					$grades = $this->region($tz_acf, $user_acf['factory'], $user_acf['delivery'], $federation_assoc);
					if ($grades) {
						$clear_users[$user_key]['match'] += $grades;
					} else {
						unset($clear_users[$user_key]);
						//echo "<p>a: $user_field";
						break;
					}

				} else if ($value['type'] == 'taxonomy') {
					// Разборка таксономий
					$score = count(array_intersect($tz_acf, $user_acf));
					//print_r($user_acf);
					if ($score == 0) {
						if ($value['noexists'] === 'exit') {
							unset($clear_users[$user_key]);
							//echo "<p>b: $user_field $user_id $value[noexists]";
							break;
						} else {
							$clear_users[$user_key]['match'] += $value['noexists'];
						}
					} else {
						$clear_users[$user_key]['match'] += 1;
					}

				} else if ($value['type'] == 'number') {
					// Если число
					if ($user_acf <= $tz_acf) {
						$clear_users[$user_key]['match'] += $value['exists']['less_or_equals']['value'];
					} else if ($user_acf < ($tz_acf / 100) * ($value['exists']['more']['exit'] + 100) && $user_acf > $tz_acf) {
						$clear_users[$user_key]['match'] += $value['exists']['more']['value'];
					} else if ($user_acf > ($tz_acf / 100) * ($value['exists']['more']['exit'] + 100)) {
						unset($clear_users[$user_key]);
						//echo "<p>c: $user_field";
						break;
					} else {
						unset($clear_users[$user_key]);
						//echo "<p>d: $user_field";
						break;
					}
				}
			}
		}
		return $clear_users;
	}

	private function membership_plan()
	{
		$membership = $this->membership;
		if ($membership !== 'str') {
			return 'member';
		} else {
			return 'nomember';
		}
	}

	private function get_membership_table()
	{
		$isnot_member = array(
			//	'field1'	=> array(
			//		'label'	=> 'Название',
			//		'key'	=> 'display_name',
			//		'type'  => 'meta'
			//	),
			'field1' => array(
				'label' => 'Название',
				'key' => 'ab_name_company',
				'class_vendor' => 'vendors__item-title',
				'type' => 'field'
			),
			//	'field2'	=> array(
			//		'label'	=> 'Сайт',
			//		'key'	=> 'sajt',
			//		'type'  => 'field'
			//	),
			'field3' => array(
				'label' => 'Регион',
				//'key'	=> 'region',
				'key' => 'Regionproizvodstva_user',
				'class_vendor' => 'vendors__item-region',
				'type' => 'field'
			),
			'field4' => array(
				'label' => 'Кол-во совпадений',
				'key' => 'count',
				'class_vendor' => 'vendors__item-concurrency',
				'type' => 'match'
			),
			'field5' => array(
				'label' => 'Узнать цену',
				'member' => 'false',
				'key' => 'takePrice',
				'type' => 'field'
			),
			'field6' => array(
				'label' => 'compare_nomember',
				'key' => 'compare',
				'type' => 'field'
			),
			'field7' => array(
				'label' => 'ЦП_nomember',
				'key' => 'cp',
				'type' => 'field'
			),
			'field8' => array(
				'label' => 'Показать контакты',
				'key' => 'show-contacts',
				'class_vendor' => 'vendors__item-show-contacts',
				'type' => 'field'
			),
		);

		$is_member = array(
			//	'field1'	=> array(
			//		'label'	=> 'Название',
			//		'key'	=> 'display_name',
			//		'type'  => 'meta'
			//	),
			'field0' => array(
				'label' => 'Логотип',
				'key' => 'ab_logo',
				'type' => 'field'
			),
			'field1' => array(
				'label' => 'Название',
				'key' => 'ab_name_company',
				'class_vendor' => 'vendors__item-title',
				'type' => 'field'
			),
			'field2' => array(
				'label' => 'Сайт',
				'key' => 'sajt',
				'class_vendor' => 'vendors__item-domain',
				'type' => 'field'
			),
			'field3' => array(
				'label' => 'Email',
				'key' => 'email',
				'type' => 'field',
				'class' => 'mail',
				'class_vendor' => 'vendor_mail vendors__item-mailto',
				'exist_id' => 'true'
			),
			'field4' => array(
				'label' => 'Телефон',
				'key' => 'telefon',
				'class_vendor' => 'vendors__item-number',
				'type' => 'field'
			),
			'field5' => array(
				'label' => 'Регион',
				//'key'	=> 'region',
				'key' => 'Regionproizvodstva_user',
				'class_vendor' => 'vendors__item-region',
				'type' => 'field'
			),
			'field6' => array(
				'label' => 'Кол-во совпадений',
				'key' => 'count',
				'class_vendor' => 'vendors__item-concurrency',
				'type' => 'match'
			),
			/*'field7' => array(
			'label' => 'Рейтинг',
			'key' => 'rating',
			'type' => 'field'
			),*/
			'field7.5' => array(
				'label' => 'Узнать цену',
				'member' => 'true',
				'key' => 'takePrice',
				'type' => 'field'
			),
			'field8' => array(
				'label' => 'Надежность',
				'key' => 'confidence',
				'class_vendor' => 'confidence_td',
				'sub_fields' => array(
					'sub_field1' => array(
						'label' => 'verified__user_vendor',
						'key' => 'verified__user_vendor',
						'type' => 'field'
					),
					'sub_field2' => array(
						'label' => 'akkreditation',
						'key' => 'vendor_accreditation',
						'type' => 'meta'
					),
					'sub_field3' => array(
						'label' => 'is_vendor_member',
						'key' => 'is_vendor_member',
						'type' => 'field'
					),
					'sub_field4' => array(
						'label' => 'antirating',
						'key' => 'antirating',
						'type' => 'field'
					),
				),
			),
			'field9' => array(
				'label' => 'Сравнить анкету с ТЗ',
				'key' => 'compare',
				'type' => 'field'
			),
			'field10' => array(
				'label' => 'ЦП',
				'key' => 'cp',
				'type' => 'field'
			),
		);

		if ($this->membership_plan() == 'member') {
			return $is_member;
		} else {
			return $isnot_member;
		}
	}

	private function output($clear_users)
	{
		if (count($clear_users) > 1) {
			$volume = array_column($clear_users, 'match');
			array_multisort($volume, SORT_DESC, $clear_users);
			// var_dump($volume);
		}
		$users = [];
		$n = 0;
		foreach ($clear_users as $user) {
			$n++;
			if (!$user['match'])
				break;
			if ($n > self::limit)
				break;
			$users[] = $user;
		}
		if (count($users) == 0) {
			return "<h3>Нет подходящих поставщиков для данного технического задания</h3>";
		}

		$current_user = wp_get_current_user();
		$table_keys = $this->get_membership_table();
		$send_tz_btn = '<div class="tz-buttons"><button id="sendVendorsEmails" class="acf-button button button-primary button-large" onclick="get_checboxes(' . $current_user->ID . ', ' . $this->id . ')">Отправить заявку всем на оценку</button><a style="margin-right: 10px;" class="tzEditBtn acf-button button button-primary button-large" href="' . get_the_permalink() . '/?action=edit">Редактировать заявку</a> <a class="acf-button button button-primary button-large" href="' . get_the_permalink() . '">Просмотреть заявку</a>';

		$html_result = '';
		// $html_result .= $send_tz_btn;
		// $html_result .= $this->btn['print'];
		// $html_result .= $this->btn['all_perspectives'];
		$html_result .= '</div>';
		//$html_result .= '<table class="fixed_headers" data-tz="' . get_the_ID() . '">';
		$isViewerMember = '';
		if (wc_memberships_get_user_active_memberships($current_user->ID)) {
			$isViewerMember .= ' member';
		}

		$html_result .= '<section class="vendors__section' . $isViewerMember . '" data-tz="' . get_the_ID() . '">';
		$html_result .= '<div>';
		$html_result .= 'Найдено поставщиков согласно критериев: ' . count($users);
		$html_result .= '</div>';
		//$html_result .= '<thead>';
		//$html_result .= '<tr>';

		//$html_result .= '<th>№</th>';

		//if ($this->membership_plan() !== 'member') {
		//	$html_result .= '<th></th>';
		//} else {
		//	$html_result .= '<th><input type="checkbox" class="select-all-tz-vendors" data-count="1000000"></th>';
		//}

		//foreach ($table_keys as $value) {
		//	if (isset($value['class'])) {
		//		$html_result .= '<th class="' . $value['class'] . '">' . $value['label'] . ': </th>';
		//	} else {
		//		$html_result .= '<th>' . $value['label'] . ': </th>';
		//	}
		//}
		//if (!is_string($this->getMails()) && count($this->getMails()) <= 3 && $this->membership_plan() !== 'member') {
		//$html_result .= "<th>Статус сообщения</th>";
		//$html_result .= '</tr>';
		//$html_result .= '</thead>';
		//$html_result .= '<tbody>';
		$html_result .= '<ul class="vendors__list">';
		$iter = 0;
		foreach ($users as $u_key => $user) {
			//$html_result .= '<tr>';
			$membershipOnUser = '';
			if (wc_memberships_get_user_active_memberships($user['userid'])) {
				$membershipOnUser .= ' active-member';
			}
			$html_result .= '<li class="vendors__item' . $membershipOnUser . '">';
			//$html_result .= '<td>';
			//$html_result .= $iter + 1;
			//$html_result .= '</td>';
			$html_result .= '<div class="vendors__item_logo-container">';
			//$html_result .= '<td>';
			$html_result .= '<input type="checkbox" data-vendor="' . $user['userid'] . '" class="select-tz-vendor vendors__item-checkbox">';
			//$html_result .= '</td>';
			if (get_field('ab_logo', 'user_' . $user['userid'])) {
				if (strlen(get_field('ab_logo', 'user_' . $user['userid'])) > 0) {
					$html_result .= '<img width="150px" src="' . get_field('ab_logo', 'user_' . $user['userid']) . '" alt="Logo" />';
				}
			}
			$html_result .= '</div>';
			$html_result .= '<ul class="vendors__item-info_list">';
			foreach ($table_keys as $value) {
				if (isset($value['class_vendor'])) {
					$class_vendor = 'class="' . $value['class_vendor'] . '"';
					if (isset($value['exist_id'])) {
						$class_vendor .= ' data-vendor="' . $user['userid'] . '"';
					}
				} else {
					$class_vendor = '';
				}


				$html_result .= '<li ' . $class_vendor . '>';

				if (isset($value['type'])) {
					switch ($value['type']) {
						case 'meta':
							$html_result .= get_the_author_meta($value['key'], $user['userid']);
							break;
						case 'field':
							if ($value['key'] === 'region' or $value['key'] === 'Regionproizvodstva_user') {
								$regions = get_field($value['key'], 'user_' . $user['userid']);
								if (!is_array($regions))
									$regions = [$regions];
								if ($this->is_taxonomy_ids($regions))
									$this->taxonomy($regions);
								foreach ($regions as $region) {
									if (is_object($region))
										$region_name = $region->name;
									else
										$region_name = $region;
									$html_result .= '<div data-type="' . $value['key'] . '"><p>Россия, </p>&#160;' . $region_name . '</div>';
								}
							} else if ($value['key'] === 'email') {
								$html_result .= '<div data-type="' . $value['key'] . '"><p>' . $value['label'] . ':</p>&#160;<span class="vendore__email-content">' . get_field($value['key'], 'user_' . $user['userid']) . '</span></div>';
							} else if ($value['key'] === 'compare') {
								$html_result .= '<a href="https://tpktrade.ru/compare?post=' . get_the_ID() . '&user=' . $user['userid'] . '" class="compare-company__vendor-page blue-vendor-btn btn__vendor-page" target="blank">Кол-во совпадений: ' . $user['match'] . '</a>';
								//if ($value['label'] === 'compare_nomember') {
								//	$html_result .= '<button class="compare-company-info__vendor-page btn__vendor-page" onClick={document.getElementById("model__get-compare").classList.remove("hidden")}>Кол-во совпадений: ' . $user['match'] . '</button>';
								//} else {
								//	$html_result .= '<a href="https://tpktrade.ru/compare?post=' . get_the_ID() . '&user=' . $user['userid'] . '" class="compare-company__vendor-page blue-vendor-btn btn__vendor-page">Кол-во совпадений: ' . $user['match'] . '</a>';
								//}
							} else if ($value['key'] == 'rating') {
								$accreditation .= get_user_meta($user['userid'], 'vendor_accreditation')[0] == 'on' ? 'on' : 'off';
								if (get_user_meta($user['userid'], 'verified__user_vendor')[0] > 0) {
									$html_result .= '<div data-type="rating"><p>Рейтинг:</p>800</div>';
								} else if ($accreditation == 'on') {
									$html_result .= '<div data-type="rating"><p>Рейтинг:</p>500</div>';
								} else {
									$html_result .= '<div data-type="rating"><p>Рейтинг:</p>300</div>';
								}
								unset($accreditation);
							} else if (strlen(get_field($value['key'], 'user_' . $user['userid'])) > 0 && $value['key'] === 'ab_name_company') {
								$html_result .= '<div data-type="' . $value['key'] . '">' . get_field($value['key'], 'user_' . $user['userid']) . '</div>';
							} else if (strlen(get_field($value['key'], 'user_' . $user['userid'])) > 0) {
								if ($value['key'] !== 'ab_logo') {
									$html_result .= '<div data-type="' . $value['key'] . '"><p>' . $value['label'] . ':</p>&#160;' . get_field($value['key'], 'user_' . $user['userid']) . '</div>';
								}
							} else if ($value['key'] === 'show-contacts') {
								$html_result .= '<button class="vendors__btn_get-info additional-company-info__vendor-page btn__vendor-page" id="show__modal_member" onClick={document.getElementById("model__get-member").classList.remove("hidden")}>Показать контакты</button>';

							} else if ($value['key'] !== 'ab_logo') {
								$html_result .= get_field($value['key'], 'user_' . $user['userid']);
							}
							break;
						case 'match':
							//$html_result .= '<div data-type="match"><p>Кол-во совпадений: ' . $user['match'] . '</p></div>';
							break;
					}
				}

				$html_result .= '</li>';
			}
			$get_mails = $this->getMails();
			$html_result .= $get_mails;
			if (!is_array($get_mails))
				$get_mails = [];
			
			if (get_user_meta($user['userid'], 'tz_mails_' . get_the_ID(), true)) {
				$html_result .= "<li class='sented_letter' id='status_$user[userid]'>Отправлено</li>";
			} else {
				$html_result .= "<li class='sented_letter' id='status_$user[userid]'></li>";
			}

			//if (isset($get_mails[$user['userid']])) {
			//	$html_result .= "<li class='sented_letter' id='status_$user[userid]'>Отправлено</li>";
			//} else {
			//	$html_result .= "<li class='sented_letter' id='status_$user[userid]'></li>";
			//}
			$html_result .= '</ul>';
			$html_result .= '<ul class="vendors__item-icons_list">';
			foreach ($table_keys as $value) {
				if (isset($value['sub_fields']) && !isset($value['type'])) {
					foreach ($value['sub_fields'] as $k => $v) {
						switch ($v['type']) {
							case 'meta':
								$accreditation .= get_user_meta($user['userid'], $v['key'])[0] == 'on' ? 'on' : 'off';
								if ($accreditation == 'on') {
									$html_result .= '<li>';
									$html_result .= '<span class="ked_pos_relative link"><a href="' . get_author_posts_url($user['userid']) . '"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M14.851 11.923c-.179-.641-.521-1.246-1.025-1.749-1.562-1.562-4.095-1.563-5.657 0l-4.998 4.998c-1.562 1.563-1.563 4.095 0 5.657 1.562 1.563 4.096 1.561 5.656 0l3.842-3.841.333.009c.404 0 .802-.04 1.189-.117l-4.657 4.656c-.975.976-2.255 1.464-3.535 1.464-1.28 0-2.56-.488-3.535-1.464-1.952-1.951-1.952-5.12 0-7.071l4.998-4.998c.975-.976 2.256-1.464 3.536-1.464 1.279 0 2.56.488 3.535 1.464.493.493.861 1.063 1.105 1.672l-.787.784zm-5.703.147c.178.643.521 1.25 1.026 1.756 1.562 1.563 4.096 1.561 5.656 0l4.999-4.998c1.563-1.562 1.563-4.095 0-5.657-1.562-1.562-4.095-1.563-5.657 0l-3.841 3.841-.333-.009c-.404 0-.802.04-1.189.117l4.656-4.656c.975-.976 2.256-1.464 3.536-1.464 1.279 0 2.56.488 3.535 1.464 1.951 1.951 1.951 5.119 0 7.071l-4.999 4.998c-.975.976-2.255 1.464-3.535 1.464-1.28 0-2.56-.488-3.535-1.464-.494-.495-.863-1.067-1.107-1.678l.788-.785z"/></svg></a></span>';
									$html_result .= '</li>';
								}
								unset($accreditation);
								break;
							case 'field':
								//if ($v['key'] == 'region') {
								//	$regions = get_field($v['key'], 'user_' . $user['userid']);
								//	foreach ($regions as $region) {
								//		$html_result .= $region->name . ' ';
								//	}
								//}
								if ($v['key'] == 'verified__user_vendor') {
									if (get_user_meta($user['userid'], 'verified__user_vendor')[0] > 0) {
										$html_result .= '<li>';
										$html_result .= '<span data-tooltip="Поставщик аккредитован" class="ked_pos_relative accreditation"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 48 48"><g fill="#118c03"><path d="M24 2L6 10v12c0 11.11 7.67 21.47 18 24 10.33-2.53 18-12.89 18-24V10L24 2zm-4 32l-8-8 2.83-2.83L20 28.34l13.17-13.17L36 18 20 34z"></path></g></svg></span>';
										$html_result .= '</li>';
									} else {
										$html_result .= '<li>';
										$html_result .= '<span data-tooltip="Поставщик не аккредитован" class="ked_pos_relative accreditation"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 48 48"><g fill="#c4c4c4"><path d="M24 2L6 10v12c0 11.11 7.67 21.47 18 24 10.33-2.53 18-12.89 18-24V10L24 2zm-4 32l-8-8 2.83-2.83L20 28.34l13.17-13.17L36 18 20 34z"></path></g></svg></span>';
										$html_result .= '</li>';
									}
								} else if ($v['key'] === 'is_vendor_member') {
									if (wc_memberships_get_user_active_memberships($user['userid'])) {
										$html_result .= '<li><span data-tooltip="Тариф оформлен" class="ked_pos_relative"><i aria-hidden="true" class="far fa-gem vendor__orange-diamond"></i></span></li>';
									} else {
										$html_result .= '<li><span data-tooltip="Тариф не оформлен" class="ked_pos_relative"><i aria-hidden="true" class="far fa-gem vendor__gray-diamond"></i></span></li>';
									}
								} else if ($v['key'] === 'antirating') {
									if (get_field('status_postavshhika', 'user_' . $user['userid'])) {
										$html_result .= '<li>';
										$html_result .= '<span data-tooltip="Поставщик в Антирейтинге" class="ked_pos_relative antirating"><svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke=""><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11 13C11 13.5523 11.4477 14 12 14C12.5523 14 13 13.5523 13 13V8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8V13ZM13 15.9888C13 15.4365 12.5523 14.9888 12 14.9888C11.4477 14.9888 11 15.4365 11 15.9888V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V15.9888Z" fill="#ff0000"></path> </g></svg></span>';
										//$html_result .= '<span data-tooltip="Поставщик в Антирейтинге" class="ked_pos_relative antirating"><img width=30 src="https://tpktrade.ru/wp-content/uploads/2022/03/exclamation.png"></span>';
										$html_result .= '</li>';
									}
								} else {
									$html_result .= get_field($v['key'], 'user_' . $user['userid']);
								}
								break;
						}
					}
				}
			}


			$html_result .= '</ul>';

			$html_result .= '<div class="vendors__item-buttons">';
			foreach ($table_keys as $value) {
				if ($value['key'] === 'cp') {
					$html_result .= '<a href = "https://tpktrade.ru/cp?user=' . $user['userid'] . '" class="additional-company-info__vendor-page btn__vendor-page vendors__btn_get-info" target="blank">Подробнее о компании</a>';
					//if ($value['label'] === 'ЦП_nomember') {
					//	$html_result .= '<button class="vendors__btn_get-info additional-company-info__vendor-page btn__vendor-page" id="show__modal_member" onClick={document.getElementById("model__get-member").classList.remove("hidden")}>Подробнее о компании</button>';
					//} else {
					//	$html_result .= '<a href = "https://tpktrade.ru/cp?user=' . $user['userid'] . '" class="additional-company-info__vendor-page btn__vendor-page vendors__btn_get-info">Подробнее о компании</a>';
					//}
				} else if ($value['key'] === 'takePrice') {
					$isViewerMember = false;
					if (wc_memberships_get_user_active_memberships($current_user->ID)) {
						$isViewerMember .= true;
					}
					if ($isViewerMember) {
						$html_result .= '<button class="vendors__btn_get-cp btn__vendor-page blue-vendor-btn btn__take-price" onclick="get_checboxes(' . $user['userid'] . ',' . get_the_ID() . ', true)">Запросить КП</button>';
					} else {
						$html_result .= '<button class="vendors__btn_get-cp btn__vendor-page blue-vendor-btn btn__take-price" onclick="freeUserWantToGetPrice(this, ' . get_the_ID() . ', ' . $user['userid'] . ', ' . $current_user->ID . ')">Запросить КП</button>';
					}
				}
			}
			$html_result .= '</div>';


			//$html_result .= '</tr>';
			$html_result .= '</li>';
			$iter++;
		}
		$html_result .= '</ul>';
		//$html_result .= '</tbody>';
		$html_result .= '</section>';
		//$html_result .= '</table>';
		$html_result .= '<div class="modal__get-member modal__vendor hidden" id="model__get-member"><h4>Чтобы получить доступ к информации о компании и контактам - перейдите на PRO</h4><a class="member__pro-btn" href="https://tpktrade.ru/lichnyj-kabinet/tarif-pro-dostup/" target="blank">Посмотреть тарифы</a><button id="btn__close-modal_member" onClick={document.getElementById("model__get-member").classList.add("hidden")}>Закрыть</button></div>';
		$html_result .= '<div class="modal__get-compare modal__vendor hidden" id="model__get-compare"><h4>Чтобы посмотреть, по каким параметрам вашего Техзадания подходит данный поставщик - оформите подписку</h4><a class="member__pro-btn" href="https://tpktrade.ru/lichnyj-kabinet/tarif-pro-dostup/" target="blank">Посмотреть тарифы</a><button onClick={document.getElementById("model__get-compare").classList.add("hidden")}>Закрыть</button></div>';
		$html_result .= '<div class="modal__get-prices modal__vendor hidden" id="model__get-prices"><h4>Вы израсходовали бесплатные запросы. Для получения полного функционала оформите тариф!</h4><a class="member__pro-btn" href="https://tpktrade.ru/lichnyj-kabinet/tarif-pro-dostup/" target="blank">Посмотреть тарифы</a><button onClick={document.getElementById("model__get-prices").classList.add("hidden")}>Закрыть</button></div>';
		//$html_result .= '<div class="tz-buttons">' . $send_tz_btn;
		//$html_result .= $this->btn['print'];
		//$html_result .= $this->btn['all_perspectives'] . '</div>';
		return $html_result;
	}

	public function result()
	{
		if ($this->have_posts()) {
			$federation_ids = $this->getFederationRegion();
			$federation_assoc = $this->federation_assoc($federation_ids);
			$clear_users = $this->GetClearUsers($federation_ids, $federation_assoc);
			// echo '<pre>';
			// print_r($clear_users['dev1']);
			// echo '</pre>';
			if (!empty($clear_users)) {
				echo $this->output($clear_users);
			} else {
				echo "<h3>Нет подходящих поставщиков для данного технического задания</h3>";
			}
			return true;
		}
	}

	private function is_taxonomy_ids($arr)
	{
		if (!is_array($arr))
			return false;
		foreach ($arr as $key => $term) {
			if (is_int($term))
				return true;
			else
				return false;
		}
	}

	private function taxonomy(&$arr)
	{
		if (!is_array($arr))
			return;
		foreach ($arr as $key => $term_id) {
			$arr[$key] = get_term_by('term_taxonomy_id', $term_id)->name;
		}
	}

	private function federation_assoc($federation_ids)
	{
		$res = [];
		foreach ($federation_ids as $fed) {
			$fed_id = $fed['federation'];
			foreach ($fed['federation_ids'] as $id) {
				$region = get_term($id)->name;
				$res[$region] = $fed_id;
			}
		}
		return $res;
	}

	private function region($tz, $factory, $delivery, $federation)
	{
		if (!is_array($tz))
			$tz = [];
		if (!is_array($factory))
			$factory = [];
		if (!is_array($delivery))
			$delivery = [];

		$this->taxonomy($factory);
		// для поля delivery форма возвращает объекты, а не названия и не id
		foreach ($delivery as &$del)
			$del = $del->name;
		$anketa = $factory; foreach ($delivery as $region)
			$anketa[] = $region;

		$tz_fed = []; foreach ($tz as $region)
			if (isset($federation[$region]))
				$tz_fed[] = $federation[$region];

		$anketa_fed = []; foreach ($anketa as $region)
			if (isset($federation[$region]))
				$anketa_fed[] = $federation[$region];

		if (array_intersect($tz, $anketa))
			return 2;
		if (array_intersect($tz_fed, $anketa_fed))
			return 1;
		return 0;
	}

	private function get_by_key($user_id, $key)
	{
		$obj = get_field_object($key, 'user_' . $user_id);
		//$this->taxonomy($obj['value']);
		return $obj ? $obj['value'] : '';
	}

}