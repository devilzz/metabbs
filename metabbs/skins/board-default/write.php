<?=flash_message_box()?>
<?=error_message_box($error_messages)?>

<form method="post" enctype="multipart/form-data" action="">
<div id="post-form">
<h1><span class="title-wrap">글쓰기</span></h1>
<table>
<? if ($guest): ?>
<tr>
	<th>이름</th>
	<td class="name"><input type="text" name="author" value="<?=$post->author?>" class="check <?=marked_by_error_message('author', $error_messages)?>"/></td>

	<th>암호</th>
	<td class="password"><input type="password" name="password" class="check <?=marked_by_error_message('password', $error_messages)?>"/></td>
</tr>
<? endif; ?>
<tr>
	<th>제목</th>
	<td colspan="3"><input type="text" name="title" size="50" value="<?=$post->title?>" id="post_title" class="check <?=marked_by_error_message('title', $error_messages)?>"/></td>
</tr>

<tr class="options">
	<th></th>
	<td colspan="3">
		<? if ($categories): ?>
		<select name="category">
		<? foreach ($categories as $category): ?>
			<option value="<?=$category->id?>" <?=$category->selected?>><?=$category->name?></option>
		<? endforeach; ?>
		</select>
		<? endif; ?>

		<? if ($admin): ?><input type="checkbox" id="post_notice" name="notice" value="1" <?=$notice_checked?> /> 공지사항<? endif; ?>
		<input type="checkbox" id="post_secret" name="secret" value="1" <?=$secret_checked?> /> 비밀글
	</td>
</tr>

<tr>
	<td colspan="4" class="body"><textarea name="body" id="post_body" cols="40" rows="12" class="check <?=marked_by_error_message('body', $error_messages)?>"><?=$post->body?></textarea></td>
</tr>

<? if ($taggable): ?>
<tr class="tag">
	<th>태그</th>
	<td colspan="3">
		<input type="text" name="tags" value="<?=$post->tags?>" size="50" id="post_tags" /><br />
		태그 사이는 쉼표(,)로 구분합니다.
	</td>
</tr>
<? endif; ?>

<? if ($preview): ?>
<tr class="preview">
	<th>미리보기</th>
	<td colspan="3"><?=$preview->body?></td>
</tr>
<? endif; ?>

<? foreach ($additional_fields as $field): ?>
<tr class="additional_field">
	<th><?=$field->name?></th>
	<td colspan="3"><?=$field->output?></td>
</tr>
<? endforeach; ?>
</table>

<? if ($uploadable): ?>
<div id="upload">
<h2>파일 올리기</h2>
<p>최대 업로드 크기: <?=$upload_limit?></p>
<ul id="uploads">
<? foreach ($attachments as $attachment): ?>
	<li><?=$attachment->filename?> <input type="checkbox" name="delete[]" value="<?=$attachment->id?>" /> 삭제</li>
<? endforeach; ?>
	<li><input type="file" name="upload[]" size="50" /></li>
</ul>
<p><a href="#" onclick="addFileEntry(); return false" class="button">파일 추가...</a></p>
</div>
<? endif; ?>

<? if ($admin): ?>
<div id="trackback">
<h2>트랙백 보내기</h2>
<ul id="trackback_input">
	<li><input type="text" name="trackback" size="63" /></li>
</ul>
</div>
<? endif; ?>
</div>

<div class="meta-nav">
	<button type="submit" name="action" value="save" class="save">
		<? if ($editing): ?>고치기<? else: ?>글쓰기<? endif; ?>
	</button>
	<button type="submit" name="action" value="preview">미리보기</button>
<? if ($link_cancel): ?><a href="<?=$link_cancel?>">취소</a><? endif; ?>
<a href="<?=$link_list?>">목록으로</a>
</div>
</form>
