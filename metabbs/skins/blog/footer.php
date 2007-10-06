		</div>
		
		<div id="sidebar">
		<h3>Admin</h3>
		<ul>
		<? if ($account->is_guest()) { ?>
			<li><?=login()?></li>
		<? } ?>
		<? if ($account->is_admin()) { ?>
			<li><?=admin()?></li>
		<? } ?>
		<? if (isset($link_new_post) && $link_new_post) { ?>
			<li><a href="<?=$link_new_post?>"><?=i('New Post')?></a></li>
		<? } ?>
		<? if (isset($link_edit) && $link_edit) { ?>
			<li><a href="<?=$link_edit?>"><?=i('Edit')?></a></li>
		<? } ?>
		<? if (isset($link_delete) && $link_delete) { ?>
			<li><a href="<?=$link_delete?>"><?=i('Delete')?></a></li>
		<? } ?>
		</ul>

		<? if ($board->use_category) { ?>
		<h3>Category</h3>
		<ul>
			<li><a href="<?=url_for($board)?>"><?=i('All')?></a></li>
		<? foreach ($board->get_categories() as $category) { ?>
			<li><?=link_to_category($category)?> (<?=$category->get_post_count()?>)</li>
		<? } ?>
		</ul>
		<? } ?>

		<h3>Recent Comments</h3>
		<ul id="recent-comments">
		<? foreach ($board->get_recent_comments(5) as $comment) { ?>
			<li><?=link_to(htmlspecialchars(utf8_strcut($comment->body, 16)), $comment)?> <cite><?=$comment->name?></cite> <abbr title="<?=meta_format_date_rfc822($comment->created_at)?>"><?=strftime("%m/%d", $comment->created_at)?></abbr></li>
		<? } ?>
		</ul>

		<p><a href="<?=url_for($board, 'rss')?>"><img src="<?=$skin_dir?>/feed.gif" alt="Feed" /></a></p>

		<br style="clear:both;" />
	</div>

	<div id="footer">
		<hr />
		<p><a href="<?=url_for_blog($board)?>"><?=$board->title?></a></p>
		<ul>
			<li>powered by <a href="http://metabbs.org/">MetaBBS</a> /
			    styled with <?=$board->style?$board->style:'basic'?></li>
		</ul>
	</div>
</div>
</div>