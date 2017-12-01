<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.45press.com
 * @since      1.0.0
 *
 * @package    Dev_Tool_Importer
 * @subpackage Dev_Tool_Importer/admin/partials
 */

$option_value = get_option( 'dtk_importer_csv' );
?>

<div id="dtk-import" class="wrap">
	<h1>Developer Toolkit Importer</h1>

	<?php
	// if no URL set.
	if ( get_option( 'dtk_importer_csv' ) === '' ) {
	?>

		<form method="post">
			<?php wp_nonce_field( 'save_dtk_csv_url', 'dtk_csv_security' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
					<th><label for="dtk_importer_csv">Add CSV URL</label></th>
					<td><input name="dtk_importer_csv" type="text" value="<?php echo esc_url( $option_value ); ?>" style="width:80%"></td>
					</tr>
					<tr>
					<th></th>
					<td><input type="submit" name="submit" class="button button-primary" value="Add CSV URL"></td>
					</tr>
				</tbody>
			</table>
		</form>

	<?php
	// url set ready for csv validation.
	} else {
	?>

		<form method="post">
			<?php wp_nonce_field( 'save_dtk_csv_url', 'dtk_csv_security' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
					<th><label for="dtk_importer_csv">Change CSV URL</label></th>
					<td><input name="dtk_importer_csv" type="text" id="dtk_importer_csv" value="<?php echo esc_url( $option_value ); ?>" style="width:80%"></td>
					</tr>
					<tr>
					<th></th>
					<td><input type="submit" name="submit" id="submit" class="button button-primary" value="Update CSV URL"></td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
		$file = new SplFileObject( $option_value );
		$file->setFlags( SplFileObject::READ_CSV );
		$file->setFlags( SplFileObject::READ_AHEAD );
		$file->setFlags( SplFileObject::SKIP_EMPTY );
		$file->setFlags( SplFileObject::DROP_NEW_LINE );

		$headers       = array();
		$whole_shabang = array();

		$i = -1;
		while ( ! $file->eof() ) {
			$array = $file->fgetcsv();
			if ( ! empty( $array[1] ) ) {
				$i++;
				if ( 0 === $i ) {
					$count = count( $array );
					foreach ( $array as $header ) {
						$headers[] = $header;
					}
				}
			}
		}
		$errors = 0;
		$total  = strval( $i );
		echo '<p>Total Rows: ' . esc_html( $total ) . '</p>';
		if ( $count >= 5 ) {
			$columns = strval( $count );
			echo '<p style="color:green">Total Column Headers: ' . esc_html( $columns ) . '</p>';
		} else {
			$columns = strval( $count );
			echo '<p style="color:red">Total Column Headers: ' . esc_html( $columns ) . '</p>';
			echo '<p style="color:red">Error: 5 headers required</p>';
			$errors++;
		}

		$l = 0;
		foreach ( $headers as $header ) {
			$l++;
			if ( 1 === $l ) {
				if ( 'id' === $header ) {
					$header_key = strval( $l );
					echo '<p style="color:green">Header ' . esc_html( $header_key ) . ': ' . esc_html( $header ) . '</p>';
				} else {
					$header_key = strval( $l );
					echo '<p style="color:red">Header ' . esc_html( $header_key ) . ': ' . esc_html( $header ) . '</p>';
					echo '<p style="color:red">Error: id</p>';
					$errors++;
				}
			} elseif ($l === 2 ) {
				if ( $header === 'skill' ) {
					echo '<p style="color:green">Header ' . strval( $l ) . ': ' . $header . '</p>';
				} else {
					echo '<p style="color:red">Header ' . strval( $l ) . ': ' . $header . '</p>';
					echo '<p style="color:red">Error: skill</p>';
					$errors++;
				}
			} elseif ( $l === 3 ) {
				if ( $header === 'level' ) {
					echo '<p style="color:green">Header ' . strval( $l ) . ': ' . $header . '</p>';
				} else {
					echo '<p style="color:red">Header ' . strval( $l ) . ': ' . $header . '</p>';
					echo '<p style="color:red">Error: level</p>';
					$errors++;
				}
			} elseif ( $l === 4 ) {
				if ( $header === 'date_completed' ) {
					echo '<p style="color:green">Header ' . strval( $l ) . ': ' . $header . '</p>';
				} else {
					echo '<p style="color:red">Header ' . strval( $l ) . ': ' . $header . '</p>';
					echo '<p style="color:red">Error: date_completed</p>';
					$errors++;
				}
			} elseif ( $l === 5 ) {
				if ( $header === 'stuff' ) {
					echo '<p style="color:green">Header ' . strval( $l ) . ': ' . $header . '</p>';
				} else {
					echo '<p style="color:red">Header ' . strval( $l ) . ': ' . $header . '</p>';
					echo '<p style="color:red">Error: stuff</p>';
					$errors++;
				}
			} else {
				echo '<p>Header ' . strval( $l ) . ': ' . $header . '</p>';
			}
		}

		if ( $errors > 0 ) {
			$num_errors = strval( $errors );
			echo '<p style="color:red">You have ' . esc_html( $num_errors ) . ' unresolved errors! Fix them then we can proceed...</p>';
		} else {
			$total = intval( $total );
			if ( 100 >= $total ) {
				$total_chunks = 1;
			} else {
				$total_chunks = (int) ceil( $total / 100 );
			}
		?>
			<form id="dtk-importer-start" method="post">
				<?php $nonce = wp_create_nonce( 'dtk_importer_security_check' ); ?>
				<input type="hidden" name="dtk_importer_security_check" value="<?php echo $nonce; ?>">
				<button type="submit" name="submit" class="button button-primary" style="display:block;margin:0 auto">Start The Import</button>
			</form>
			<div style="margin-top:20px">
				<h2 style="text-align:center">Keep this page open until the upload completes, don't refresh or close the page or else you will suffer dire consequences!!</h2>
				<h3 style="text-align:center">(  Not really but you will have to start all over  <span style="font-size:26px">.</span><span style="font-size:26px">.</span><span style="font-size:26px">.</span>  )</h3>
				<div id="dtk-import-reload">
				</div>
			</div>
		<?php
		}
	}
	?>
</div><!-- .wrap -->

<script>
jQuery(document).ready(function($){
});
</script>
