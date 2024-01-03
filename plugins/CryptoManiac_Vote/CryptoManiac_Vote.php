<?php
/*
Plugin Name: CryptoManiac Plugin
Description: A simple voting system for articles.
Version: 1.0
Author: Marco Grossi - Link1 Studios
*/

class VotingPlugin {

    public function __construct() {
        // Hook into WordPress actions to initialize the plugin
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_ajax_process_vote', array($this, 'process_vote'));
        add_action('wp_ajax_nopriv_process_vote', array($this, 'process_vote'));
        add_action('add_meta_boxes', array($this, 'add_voting_meta_box'));

        // Hook into the_content to display voting buttons after post content
        add_filter('the_content', array($this, 'display_voting_buttons'));
    }

    public function enqueue_scripts() {
        // Enqueue necessary scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('voting-plugin', plugin_dir_url(__FILE__) . 'js/CryptoManiac_Vote.js', array('jquery'), '1.0', true);
        wp_localize_script('voting-plugin', 'voting_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function enqueue_styles() {
        // Enqueue styles
        wp_enqueue_style('voting-styles', plugin_dir_url(__FILE__) . 'css/CryptoManiac_Vote.css', array(), '1.0', 'all');
    }

   	public function display_voting_buttons($content) {
        // Display voting buttons and results on single post
        if (is_single()) {
            $post_id = get_the_ID();

			$voting_results = $this->get_voting_results($post_id);
            ob_start(); // Start output buffering
            	include(plugin_dir_path(__FILE__) . 'templates/voting-template.php');
           	 	$voting_template = ob_get_clean(); // Get the buffered content

            	// Append the voting template after the post content
            	$content .= $voting_template;
        }
         return $content;
    }

    public function process_vote() {
        // Process AJAX request for voting

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $vote_action = isset($_POST['vote_action']) ? sanitize_text_field($_POST['vote_action']) : '';

        if ($this->validate_vote_request($post_id, $vote_action)) {
            $user_has_voted = $this->has_user_voted($post_id);

            if (!$user_has_voted) {
                $this->update_voting_data($post_id, $vote_action);
                $voting_results = $this->get_voting_results($post_id);

                wp_send_json_success(array(
                    'message' => 'Vote submitted successfully!',
                    'results' => $voting_results,
                ));
            } else {
                wp_send_json_error(array(
                    'message' => 'You have already voted for this article.',
                ));
            }
        } else {
            wp_send_json_error(array(
                'message' => 'Invalid parameters.',
            ));
        }
        error_log('Voting AJAX Request: ' . print_r($_REQUEST, true));
    }

    public function has_user_voted($post_id) {
        // Check if the current user has already voted for the current post
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $voted_ips = get_post_meta($post_id, 'voted_ips', true);

        return is_array($voted_ips) && in_array($user_ip, $voted_ips);
    }

    public function add_voting_meta_box() {
        // Add meta box to post edit screen to display voting results
        add_meta_box('voting-results', 'Voting Results', array($this, 'display_voting_results_meta_box'), 'post', 'side');
    }

    public function display_voting_results_meta_box($post) {
        // Display voting results in the meta box
        $voting_results = $this->get_voting_results($post->ID);

        echo '<p>Positive: ' . esc_html($voting_results['positive']) . '</p>';
        echo '<p>Negative: ' . esc_html($voting_results['negative']) . '</p>';
        echo '<p>Average Percentage: ' . esc_html($voting_results['average_percentage']) . '%</p>';
    }

    private function validate_vote_request($post_id, $vote_action) {
        // Validate the vote request parameters
        return $post_id > 0 && in_array($vote_action, array('positive', 'negative'));
    }

    private function update_voting_data($post_id, $vote_action) {
        // Update voting data in the database or any storage mechanism
        $voting_data = get_post_meta($post_id, 'voting_data', true);

        if (!$voting_data) {
            $voting_data = array('positive' => 0, 'negative' => 0);
        }

        if ($vote_action === 'positive') {
            $voting_data['positive']++;
        } elseif ($vote_action === 'negative') {
            $voting_data['negative']++;
        }

        // Save the updated voting data
        update_post_meta($post_id, 'voting_data', $voting_data);

        // Save the user's IP to prevent multiple votes
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $voted_ips = get_post_meta($post_id, 'voted_ips', true);

        if (!$voted_ips) {
            $voted_ips = array();
        }

        $voted_ips[] = $user_ip;
        update_post_meta($post_id, 'voted_ips', $voted_ips);
    }

    private function get_voting_results($post_id) {
        // Retrieve and calculate voting results
        $voting_data = get_post_meta($post_id, 'voting_data', true);

        if (!$voting_data) {
            $voting_data = array('positive' => 0, 'negative' => 0);
        }

        // Calculate average percentage
        $total_votes = $voting_data['positive'] + $voting_data['negative'];
        $average_percentage = $total_votes > 0 ? (float)($voting_data['positive'] / $total_votes) * 100 : 0;

        return array(
            'positive' => $voting_data['positive'],
            'negative' => $voting_data['negative'],
            'average_percentage' => round($average_percentage, 2),
        );
    }
}


// Instantiate the plugin class
$voting_plugin = new VotingPlugin();

