<? if ($comment_url): ?>
	<form method="post" action="<?=$comment_url?>" id="<?=$form_id?>">
	<? if ($guest): ?>
	<p>
		<label for="comment_author">이름</label>
		<input type="text" name="author" value="<?=$comment_author?>" id="comment_author" />

		<label for="comment_password">암호</label>
		<input type="password" name="password" id="comment_password" />
	</p>
	<? endif; ?>
	<p><textarea name="body" cols="40" rows="5"><?=$comment_body?></textarea></p>
<? if ($board->use_captcha() && isset($captcha) && $captcha->ready()): ?>
	<p>
		<label for="recaptcha_challenge_field">CAPTCHA</label>
		<?= $captcha->get_html() ?>
		<? if (!empty($captcha->error)): ?>
		<span style="captcha notice"><?=i($captcha->error)?></p>
		<? endif; ?>
    </p>
<? endif; ?>
	<div><input type="submit" value="댓글 달기" class="button" />
	<? if ($link_cancel): ?><a href="<?=$link_cancel?>" class="button dialog-close">취소</a><? endif; ?></div>
	</form>
<? endif; ?>