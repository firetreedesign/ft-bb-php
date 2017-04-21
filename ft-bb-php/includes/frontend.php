<?php
try {
	if ( ! file_exists( $module->ft_get_file_path() ) ) {
		$module->ft_save_file();
	}
	include $module->ft_get_file_path();
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
