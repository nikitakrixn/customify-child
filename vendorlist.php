<?php
/**
 * 
 * Template Name: Vendor List
 * Template Post Type: page
 * 
 */
//get_header();

$number = 4000;
$args = array(
	'role'   => 'wcfm_vendor',
	'fields' => array('display_name', 'ID', 'user_email', 'user_login'),
	'offset' => $paged ? ($paged - 1) * $number : 0,
	'number' => $number,
	);
if(isset($_GET) && isset($_GET['search'])){
	$args['search_columns'] = array('email');
	$args['search'] = $_GET['search'];
}
$user_query = new WP_User_Query($args);
$users = $user_query->get_results();
$total_users = $user_query->get_total();
$user = wp_get_current_user();

if ( in_array( 'administrator', (array) $user->roles ) || in_array( '_', (array) $user->roles )) {
	$permiss = 'true';
}else{
	$permiss = 'false';
}
  
?>


<div>

	<?php
	if(!isset($_GET['vendor_id'])){
		?>
		 <form method="get">
		<input type="text" placeholder="Поиск по Email" name="search" value="<?php echo isset($_GET) ? $_GET['search'] : ""; ?>">
	</form>
	<table>
		<thead>
			<tr>
				<th>Бренд</th>
				<th>Юр. название</th>
				<th>Сайт</th>
				<th>Email</th>
				<th>НапрР</th>
				<th>ТипО</th>
                        <th>СфП</th>
				<th>ИНН ЮД</th>
				<th>Регион</th>
				<?php
				if($permiss == 'true'){
					?>
					<th></th>
					<?php
				}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($users as $key => $value) {
				?>
				<tr>
					<?php
					echo "<td>";
					echo $value->display_name !== "" ? $value->display_name : "-";
					echo "</td>";

					echo "<td>";
					echo get_field('kratkoe_naimenovanie','user_' . $value->ID) ? get_field('kratkoe_naimenovanie','user_' . $value->ID) : "-";
					echo "</td>";

					echo "<td>";
					echo get_field('sajt','user_' . $value->ID) ? get_field('sajt','user_' . $value->ID) : "-";
					echo "</td>";


 			 		echo "<td>";
					echo get_field('email','user_' . $value->ID) ? get_field('email','user_' . $value->ID) : "-";
					echo "</td>";

					echo "<td>";
                              $nrs = get_field('field_621e7358a6ab5','user_' . $value->ID) ? get_field('field_621e7358a6ab5','user_' . $value->ID) : "-";
                              if( $nrs ): 
                                 foreach( $nrs as $nr ): echo $nr; 
                              endforeach;
                              endif;
                              echo "</td>";


					echo "<td>";
					$vd = get_field('field_61e035a2e79fe','user_' . $value->ID);
					if ($vd) {
						 $vd = get_term_by('term_taxonomy_id', $vd)->name;
					} else {
						$vd = '-';
					}
					echo $vd;
					echo "</td>";

					echo "<td>";
					$vd = get_field('field_61e02fb11a939','user_' . $value->ID);
					if ($vd) {
						 $vd = get_term_by('term_taxonomy_id', $vd)->name;
					} else {
						$vd = '-';
					}
					echo $vd;
					echo "</td>";


					echo "<td>";
					echo get_field('field_61ed104754acd','user_' . $value->ID) ? get_field('field_61ed104754acd','user_' . $value->ID) : "-";
					echo "</td>";


					echo "<td>";
					$region = get_field('Regionproizvodstva_user','user_' . $value->ID);
					if ($region) {
						 $region = get_term_by('term_taxonomy_id', $region)->name;
					} else {
						$region = '-';
					}
					echo $region;
					echo "</td>";

					if($permiss == 'true'){
						//echo "<td><label for='edit_user$key'> Изменить</label><input type='radio' id='edit_user$key' name='edit_user' value='$value->ID' class='edit_button'></td>";
						//echo "<td><a href='?vendor_id=" . $value->ID . "' target='_blank'>Изменить</a></td>";
						  echo "<td><a href='https://tpktrade.ru/profil-postavshhika/?vendor_id=" . $value->ID . "' target='_blank'>Изменить</a></td>";
					}
					?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
		<?php
		if($total_users > $number){

			$pl_args = array(
			'base'	 => add_query_arg('paged','%#%'),
			'format'   => '',
			'total'	=> ceil($total_users / $number),
			'current'  => max(1, $paged),
			);
		
			// for ".../page/n"
			if($GLOBALS['wp_rewrite']->using_permalinks())
			$pl_args['base'] = user_trailingslashit(trailingslashit(get_pagenum_link(1)).'page/%#%/', 'paged');
		
			echo paginate_links($pl_args);
		}
	}

	?>
</div>



<?php
if ($permiss == 'true' && isset($_POST) && isset($_GET['vendor_id'])) {
	acfe_form('ank_postavschik');
}
//get_footer();