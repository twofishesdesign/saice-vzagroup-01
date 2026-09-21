<?php
/**
 * Displays course information for a user
 *
 * Available:
 * $user_id
 * $courses_registered: course
 * $course_progress: Progress in courses
 * $quizzes
 *
 * @since 2.1.0
 *
 * @package LearnDash\Course
 */

/**
 * Course registered
 */

global $pagenow;

$shortcode_atts_json = htmlspecialchars( json_encode( $shortcode_atts ) );
?>
<div id="ld_course_info" class="ld_course_info" data-shortcode-atts="<?php echo $shortcode_atts_json; ?>">

	<!-- Course info shortcode -->
	<?php if ( ( $pagenow != 'profile.php' ) && ( $pagenow != 'user-edit.php' ) ) { ?>
		<?php if ( $courses_registered ) : ?>
			<div id='ld_course_info_mycourses_list' class="ld_course_info_mycourses_list">
				<h4><?php echo sprintf( _x( 'You are registered for the following %s', 'You are registered for the following courses', 'learndash' ), learndash_get_custom_label_lower( 'courses' ) ); ?></h4>
				<div class="ld-courseregistered-content-container"><?php

				$template_file = SFWD_LMS::get_template(
					'course_registered_rows',
					array(
						'courses_registered' => $courses_registered,
						'shortcode_atts' => $shortcode_atts
					),
					null,
					true
				);

				if ( ! empty( $template_file ) ) {
					include $template_file;
				}

				?></div>
				<div class="ld-course-registered-pager-container"><?php
					echo SFWD_LMS::get_template(
						'learndash_pager.php',
						array(
						'pager_results' => $courses_registered_pager,
						'pager_context' => 'course_info_courses'
						)
					);
					?>
				</div>
			</div>
			<div style="clear:both"></div>

		<?php endif; ?>
	<?php } ?>

	<?php
		if (is_admin()) {
			$current_screen = get_current_screen();

			if ( ( $pagenow == 'profile.php' ) || ( $pagenow == 'user-edit.php' ) || ($current_screen->id == 'learndash-lms_page_group_admin_page' ) ) {
				echo do_shortcode('[ld_user_course_points user_id="'. $user_id .'" context="profile"]');

				if ( ( learndash_is_admin_user() ) || ( ( learndash_is_group_leader_user() ) && ( learndash_is_group_leader_of_user( get_current_user_id(), $user_id ) ) ) ) {
					?><p><label for="learndash-course-points-user"><strong><?php printf( _x( 'Extra %s points', 'placeholders: Course Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></strong></label> <input id="learndash-course-points-user" name="learndash_course_points" type="number" min="0" step="any" value="<?php echo learndash_format_course_points( get_user_meta( $user_id, 'course_points', true ) ) ?>" /><?php } ?></p>
					<?php
				}
			}
		?>
	<?php /* Course progress */ ?>
	<?php if ( !empty( $course_progress ) ) : ?>
		<div id="course_progress_details" class="course_progress_details">
			<h4><?php printf( _x( '%s progress details:', 'Course progress details Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></h4>
			<?php
				if ( learndash_show_user_course_complete( $user_id ) ) {
					?>
					<input type="hidden" id="user-progress-<?php echo $user_id ?>" name="user_progress[<?php echo $user_id ?>]" value="<?php echo htmlspecialchars( json_encode( array('course' => array(), 'quiz' => array()), JSON_FORCE_OBJECT ) ); ?>" />
					<input type="hidden" name="user_progress-<?php echo $user_id ?>-nonce" value="<?php echo wp_create_nonce( 'user_progress-'. $user_id ) ?>" />
					<?php
				}
				?><div class="ld-course-progress-content-container"><?php
				$template_file = SFWD_LMS::get_template(
					'course_progress_rows',
					array(
						'user_id' => $user_id,
						'course_progress' => $course_progress,
						'shortcode_atts' => $shortcode_atts
					),
					null,
					true
				);
				if ( ! empty( $template_file ) ) {
					include $template_file;
				}

				?></div><?php
				?><div class="ld-course-progress-pager-container"><?php
				echo SFWD_LMS::get_template(
					'learndash_pager.php',
					array(
					'pager_results' => $course_progress_pager,
					'pager_context' => 'course_info_courses'
					)
				);
				?></div><div style="clear:both"></div><?php
			?>
		</div>
		<br>
	<?php endif; ?>

	<?php /* Quizzes */ ?>
	<?php if ( $quizzes ) : ?>
		<div id="quiz_progress_details" class="quiz_progress_details">
			<?php
				global $learndash_assets_loaded;

				if ( !isset( $learndash_assets_loaded['scripts']['learndash_template_script_js'] ) ) {

					$filepath = SFWD_LMS::get_template( 'learndash_template_script.js', null, null, true );
					if ( !empty( $filepath ) ) {
						wp_enqueue_script( 'learndash_template_script_js', learndash_template_url_from_path( $filepath ), array( 'jquery' ), LEARNDASH_SCRIPT_VERSION_TOKEN, true );
						$learndash_assets_loaded['scripts']['learndash_template_script_js'] = __FUNCTION__;

						$data = array();
						$data['ajaxurl'] = admin_url('admin-ajax.php');
						$data = array( 'json' => json_encode( $data ) );
						wp_localize_script( 'learndash_template_script_js', 'sfwd_data', $data );
					}
				}
				LD_QuizPro::showModalWindow();
			?>
			<h4><?php echo sprintf( _x( 'You have taken the following %s:', 'You have taken the following quizzes:', 'learndash' ), learndash_get_custom_label_lower( 'quizzes' ) ); ?></h4>

			<?php /* The confirm delete quiz message should not contain HTML. Use \r\n for line breaks */ ?>
			<div id="ld-confirm-quiz-delete-message" style="display:none"><?php echo sprintf( _x( 'Are you sure that you want to remove this %s item?', 'placeholder: Quiz', 'learndash' ), learndash_get_custom_label_lower( 'quiz' ) )  ?></div>

			<div class="ld-quiz-progress-content-container">
			<?php
				$template_file = SFWD_LMS::get_template(
					'quiz_progress_rows',
					array(
						'user_id' => $user_id,
						'quizzes' => $quizzes,
						'shortcode_atts' => $shortcode_atts
					),
					null,
					true
				);

				if ( ! empty( $template_file ) ) {
					include $template_file;
				}

			?></div><?php
			?><div class="ld-quiz-progress-pager-container"><?php
			echo SFWD_LMS::get_template(
				'learndash_pager.php',
				array(
				'pager_results' => $quizzes_pager,
				'pager_context' => 'course_info_quizzes'
				)
			);
			?></div><div style="clear:both"></div><?php
			?>
		</div>
	<?php endif; ?>

	<!-- TFD -->
	<?php if ( !empty( $course_progress ) ) :

		echo '<h3>Results</h3>';

		foreach ( $course_progress as $course_id => $course_data ) {

				if ( !empty($course_data) ) {

					echo '<div style="border: 1px solid #8c8f94; padding: 0 12px 15px; margin-bottom: 15px;">';

				?><h2 class="learndash-profile-course-title" style="color: #2271b1;"><?php echo get_the_title( $course_id ) ?></h2><?php

				if ( $quizzes ) :

					// Get unique quizzes (remove duplicates)
					$quizzes_unique = array();
					foreach ( $quizzes as $k => $v ) {
						$quizzes_unique[] = $v['quiz'];
					}
					$quizzes_unique = array_unique($quizzes_unique);

					// Get maximum value
					$quiz_keys = array();

					foreach ($quizzes_unique as $key => $quiz_id) {

						$duplicate_comparison = array();
						foreach ( $quizzes as $k => $v ) {
							if ( $v['quiz'] == $quiz_id) {
								$duplicate_comparison[$k] = $v['percentage'];
							}
						}

						$maxs = array_keys($duplicate_comparison, max($duplicate_comparison));

						$quiz_keys[] = $maxs[0];
					}

					// Loop through quiz keys - output values + calculate total result
					$score_total = $question_total = $result = 0;
					foreach ( $quiz_keys as $quiz_key ) {

						$quiz = get_post( $quizzes[$quiz_key]['quiz'] );

						if ( isset( $quizzes[$quiz_key]['course'] ) && $quizzes[$quiz_key]['course'] == $course_id ) {
							echo '<span style="color: #2271b1;"><strong>'.$quiz->post_title . '</strong></span> - <strong>'.$quizzes[$quiz_key]['percentage'].'%</strong><br>';
							echo 'Score: '. $quizzes[$quiz_key]['score'] . ' out of ' . $quizzes[$quiz_key]['question_show_count'] . ' questions<br>';
							echo 'Points: '. $quizzes[$quiz_key]['points'] . '/' . $quizzes[$quiz_key]['total_points'] . '<br><br>';
							$score_total += $quizzes[$quiz_key]['score'];
							$question_total += $quizzes[$quiz_key]['question_show_count'];
						}

					}

					// Status
					echo '<br><strong>STATUS:</strong> ';
					if ( $course_data['completed'] == $course_data['total'] ) {
						echo ' <span style="color: #2271b1;">Completed</span>';
					} else if ( $course_data['completed'] == 0 ) {
						echo ' <span style="color: #2271b1;">Not Started</span>';
					} else {
						echo ' <span style="color: #2271b1;">In Progress</span> ';
					}

					// Results
					echo '<br><strong>TOTAL SCORE:</strong> ' . $score_total . ' / ' . $question_total . '<br>';

					$result = ($score_total / $question_total) * 100;

					echo '<strong>RESULT:</strong> <span style="font-size: 24px; line-height: 1.4em; font-weight: 700; margin-left: 6px;">' . $result . '%</span> ';

					if ( $course_data['completed'] == $course_data['total'] ) {
						echo '<span style="font-size: 20px; font-weight: 700;">';
						if  ($result >= 70) {
							echo '<span style="color: green">PASS</span>';
						} else {
							echo '<span style="color: red">FAIL</span>';
						}
						echo '</span>';
					}

				echo '</div>';

			endif;

			}

		}

	endif; ?>
	<!-- TFD : END -->

	<!-- End Course info shortcode -->
</div>
