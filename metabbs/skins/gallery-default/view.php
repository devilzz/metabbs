<div id="post">
	<h1><span class="title-wrap"><?=$post->title?></span></h1>

	<table>
	<tr>
		<td id="post-side">
			<div class="author"><?=$post->author?></div>
			<div class="date"><?=$post->date?></div>
		</td>

		<td id="post-content">
			<? if ($attachments): ?>
			<ul id="attachments">
			<? foreach ($attachments as $attachment): ?>
				<li>
					<? if ($attachment->thumbnail_url): ?><a href="<?=$attachment->url?>" rel="lightbox[images]"><img src="<?=$attachment->thumbnail_url?>" alt="<?=$attachment->filename?>" /></a> <br /><? endif; ?>
					<a href="<?=$attachment->url?>"<? if ($attachment->thumbnail_url): ?> rel="lightbox[images]"<? endif; ?>><?=$attachment->filename?></a> (<?=$attachment->size?>)
				</li>
			<? endforeach; ?>
			</ul>
			<? endif; ?>

			<div class="body"><?=$post->body?></div>

			<? if ($taggable and $tags): ?>
			<p id="tags">태그: 
			<? foreach ($tags as $tag): ?>
				<a href="<?=$tag->url?>"><?=$tag->name?></a><? if (!$tag->last): ?>,</a><? endif; ?>
			<? endforeach; ?>
			</p>
			<? endif; ?>

			<? if ($signature): ?>
			<div class="signature"><?=$signature?></div>
			<? endif; ?>
		</td>
	</tr>
	</table>

<div id="responses">
<? if ($post->trackback_url): ?>
<div id="trackbacks">
	<p id="trackback-url">트랙백 주소: <?=$post->trackback_url?></p>
<? if ($trackbacks): ?>
	<ol>
	<? foreach ($trackbacks as $trackback): ?>
		<li>
			<a href="<?=$trackback->url?>"><?=$trackback->title?></a> from <?=$trackback->blog_name?>
			<? if ($trackback->delete_url): ?><a href="<?=$trackback->delete_url?>">삭제</a><? endif; ?>
		</li>
	<? endforeach; ?>
	</ol>
<? endif; ?>
</div>
<? endif; ?>

<div id="comments">
	<ol>
	<? foreach ($comments as $comment): ?>
		<? include "_comment.php"; ?>
	<? endforeach; ?>
	</ol>

	<? include "comment_form.php"; ?>

	<script type="text/javascript">
	Event.observe('comment-form', 'submit', function (event) {
		addComment('comment-form', $$('#comments ol')[0])
		Event.stop(event);
	});
	</script>
</div>
</div>
</div>

<div id="meta-nav">
<? if ($link_list): ?><a href="<?=$link_list?>">목록보기</a> <? endif; ?>
<? if ($link_new_post): ?><a href="<?=$link_new_post?>">글쓰기</a> <? endif; ?>
<? if ($link_edit): ?><a href="<?=$link_edit?>">고치기</a> <? endif; ?>
<? if ($link_delete): ?><a href="<?=$link_delete?>" class="dialog">지우기</a> <? endif; ?>
</div>

<script type="text/javascript">
var MetaBBS = {
    skinPath: '<?=$skin_dir?>'
}
</script>
<script type="text/javascript" src="<?=$skin_dir?>/lightbox/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="<?=$skin_dir?>/lightbox/lightbox.js"></script>
