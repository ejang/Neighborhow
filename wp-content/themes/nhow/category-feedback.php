<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div id="content">
				<h3 class="page-title">Neighborhow Feedback</h3>
				<div class="intro-block">Help make Neighborhow better by telling us about the features and content you&#39;d like to see. Vote on these popular ideas, explore all the feedback, or give us your own feedback.</div>
					
				<div id="list-feedback">
					<div class="intro-block-button"><a id="addfdbk" class="nh-btn-green" href="<?php echo $app_url;?>/add-feedback" rel="tooltip" data-placement="bottom" data-title="<strong>Please sign in before giving feedback.</strong>">Give Feedback</a></div>
						<ul class="list-feedback">

<?php
// Up to 10 feedback that have been voted on
$sql = "SELECT *, SUM(vote) as total FROM nh_wdpv_post_votes LEFT JOIN nh_posts ON nh_posts.ID=nh_wdpv_post_votes.post_id GROUP BY post_id ORDER BY total DESC LIMIT 10";	
$voted_posts = $wpdb->get_results($sql, ARRAY_A);
$total = count($voted_posts);
$total = $total - 1;

// If there are any fdbk posts that have been voted on
// Show them by total and by date
// TODO - figure out how to integrate the two lists in 1 select

if ($total > 0) {
	for ($i=0;$i<=$total;$i++) {
		$vote_post = $voted_posts[$i]['ID'];
		echo '<li class="list-vote" id="post-'.$vote_post.'">';
		echo '<div class="vote-box">'.wdpv_get_vote(true, $vote_post).'</div>';
		echo '<div class="vote-question"><strong><a href="'. get_permalink($vote_post).'" title="View this post">'.get_the_title($vote_post).'</a></strong>';
//		echo '<p>';
//		$tmp = get_excerpt_by_id($vote_post);
//		$link = nh_continue_reading_link();
//		$excerpt = trim_by_words($tmp,16,$link);
//		echo $excerpt;
//		echo '</p';
		$tmp_num = get_comments_number($vote_post);
		if ( $tmp_num == 0 ) { } 
		elseif ( $tmp_num > 1 ) {
			$comments = $tmp_num . __(' comments');
		} else {
			$comments = __('1 comment');
		}		
		echo '<p class="comment-meta"><span class="byline">'.$comments.'</span></p>';
		echo '<p class="comment-meta"><span class="byline">in </span>';
		$category = get_the_category($vote_post);
		foreach ($category as $cat) {
			echo '<a href="'.$app_url.'/feedback/'.$cat->slug.'" title="See all feedback in '.$cat->name.'">';
			echo $cat->name;
			echo '</a>';
		}
		echo '</p>';
		echo '</div></li>';
		echo '</ul>';
	}
}

// Show any fdbk posts by date if no votes yet
if ($total < 0) {
	$fdbk_cat = get_cat_ID('feedback');
	$fdbk_args = array(
		'post_status' => 'publish',
		'cat' => $fdbk_cat
	);
	$fdbk_query = new WP_Query($fdbk_args);	
	if (!$fdbk_query->have_posts()) : ?>
		<li>Looks like there&#39;s no feedback yet. Give your feedback!</li>
<?php else: ?>
<?php while($fdbk_query->have_posts()) : $fdbk_query->the_post();?>
			<li class="list-vote" id="post-<?php echo $post->ID; ?>">
				<div class="vote-box"><?php wdpv_vote(false); ?>
				</div>
				<div class="vote-question"><strong><a href="<?php the_permalink();?>" title=""><?php the_title();?></a></strong>
					<!--p>
<?php 
//$tmp = get_the_excerpt();
//$link = nh_continue_reading_link();
//$excerpt = trim_by_words($tmp,14,$link);
//echo $excerpt;?>
					</p-->
					<p class="comment-meta"><span class="byline"><?php comments_number( '', '1 comment', '% comments' ); ?></span></p>
					<p class="comment-meta"><span class="byline">in </span>
<?php 
$category = get_the_category(); 
foreach ($category as $cat) {
echo '<a href="'.$app_url.'/feedback/'.$cat->slug.'" title="">';
echo $cat->name;
echo '</a>';
}
?>
					</p>								
				</div>							
			</li>
<?php endwhile; ?>			
<?php endif; 
wp_reset_query();?>								
<?php
} // end if count < 0
?>
		</ul>
					</div><!-- / list-feedback-->
			</div><!--/ content-->
<?php get_sidebar('feedback');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>