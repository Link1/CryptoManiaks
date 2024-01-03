<?php
$post_id = get_the_ID();
$has_voted = $this->has_user_voted($post_id);
$voting_results = $this->get_voting_results($post_id);

// HTML and PHP logic for displaying the voting template
echo '<div id="voting_front">';
if ($has_voted) {
    // Display content for users who have voted
    echo '<p>Thank you for your feedback. ';
    echo '<div id="cmpositive"> ' . esc_html($voting_results['average_percentage']) . ' %</div>';
    echo '<div id="cmnegative"> ' . esc_html(100 - $voting_results['average_percentage']) . ' %</div>';
    echo '</p>';
} else {
    // Display content for users who have not voted
    echo '<p><b>Was this article helpful?</b>';
    echo '<button id="cmpositive" class="voting-button" data-post-id="'.$post_id.'" data-action="positive">Yes</button>';
    echo '<button id="cmnegative" class="voting-button" data-post-id="'.$post_id.'" data-action="negative">No</button>';
    echo '</p>';
}
echo "</div>";
?>
