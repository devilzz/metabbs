<form method="post" enctype="multipart/form-data">
<div id="post-form">
<h1><div class="title-wrap">글쓰기</div></h1>
<table>
<? if ($guest): ?>
<tr>
	<th>이름</th>
	<td class="name"><input type="text" name="author" value="<?=$post->author?>" /></td>

	<th>암호</th>
	<td class="password"><input type="password" name="password" /></td>
</tr>
<? endif; ?>

<tr>
	<th>제목</th>
	<td colspan="3"><input type="text" name="title" size="50" value="<?=$post->title?>" id="post_title" /></td>
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

		<? if ($admin): ?><input type="checkbox" name="notice" value="1" <?=$notice_checked?> /> 공지사항<? endif; ?>
		<input type="checkbox" name="secret" value="1" <?=$secret_checked?> /> 비밀글
	</td>
</tr>

<tr>
	<td colspan="4" class="body"><textarea name="body" id="post_body" cols="40" rows="12"><?=$post->body?></textarea></td>
</tr>

<? foreach ($additional_fields as $field): ?>
<tr>
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
</div>

<div id="meta-nav">
<? if ($editing): ?><input type="submit" value="고치기" class="button" /><? else: ?><input type="submit" value="글쓰기" class="button" /><? endif; ?>
<? if ($link_cancel): ?><a href="<?=$link_cancel?>">취소</a><? endif; ?>
<a href="<?=$link_list?>">목록으로</a>
</div>
</form>
