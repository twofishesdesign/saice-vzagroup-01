<?php

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Learndash records filter.
 */

add_filter('wpex_learndash_records', 'wpex_learndash_records', 10, 2);

function wpex_learndash_records($records, $user_ID)
{
	$get_records = wpex_learndash_get_records($user_ID);

	if (!empty($get_records)) {
		return $get_records;
	}

	return $records;
}

/**
 * Learndash Get Records.
 */

function wpex_learndash_get_records(int $user_ID): array
{
	$courses = get_posts( array(
		'post_type'      => 'sfwd-courses',
		'post_status'    => 'publish',
		'posts_per_page' => -1, // Get all courses
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	$records = [];

	if ( ! empty( $courses ) ) {
		foreach ( $courses as $course ) {
			$course_ID    = $course->ID;
			$course_title = $course->post_title;
			$certificate_link = learndash_get_course_certificate_link($course_ID, $user_ID);

			$progress = learndash_user_get_course_progress($user_ID, $course->ID);

			$quiz_results = wpex_learndash_get_latest_quiz_results($course_ID, $user_ID);

			if ($progress['status'] === 'completed' && $quiz_results > 75) {
				$records[] = [
					'id' => $course_ID,
					'type' => 'course'
				];
			}
		}
	}

	return $records;
}

/**
 * LearnDash Utility function to return the latest quiz result items
 * @param int $quiz_id The Quiz ID Post to return
 * @param int $user_id The specific user ID. Optional. Will use current user if not set.
 * @return array the attay of quiz information. Will look something like:
 *	Array(
 *		[quiz] => 49
 *		[score] => 1
 *		[count] => 1
 *		[question_show_count] => 1
 *		[pass] => 1
 *		[rank] => -
 *		[time] => 1490300513
 *		[pro_quizid] => 3
 *		[points] => 1
 *		[total_points] => 1
 *		[percentage] => 100
 *		[timespent] => 2.116
 *		[has_graded] => 
 *		[statistic_ref_id] => 1
 *		[started] => 1490300510
 *		[completed] => 1490300512
 *	)
 */
function wpex_learndash_get_latest_quiz_results( $course_id, $user_id )
{
	// Retrieve all quiz attempts for the user
	$quiz_attempts = get_user_meta( $user_id, '_sfwd-quizzes', true );

	if ( ! empty( $quiz_attempts ) && is_array( $quiz_attempts ) ) {

		foreach ( $quiz_attempts as $attempt ) {
			// Filter out quizzes that do not belong to this specific course
			if ( isset( $attempt['course'] ) && intval( $attempt['course'] ) === $course_id ) {
				
				// Extract core details
				$quiz_post_id   = $attempt['quiz']; 
				$quiz_title     = get_the_title( $quiz_post_id );
				
				// $score          = $attempt['score'];       // Points earned
				// $count          = $attempt['count'];       // Total possible points
				$percentage     = $attempt['percentage'];  // Score percentage
				// $pass_status    = !empty( $attempt['pass'] ) ? 'Passed' : 'Failed';
				// $date_taken     = date_i18n( get_option( 'date_format' ), $attempt['time'] );

				if ($percentage > 75) {
					return $percentage;
				}
			}
		}
	}

	return 0;
}
