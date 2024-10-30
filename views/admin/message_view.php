<?php
/** View Message Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/message_view.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			4/11/2018
*/
?>

<div class="wrap">
	<h2>KofC State: Message</h2>

	<p><strong>To: </strong> <?= htmlentities($message['to_name']) ?></p>
	<p><strong>From: </strong> <?= htmlentities($message['name']) ?> &nbsp; <a href="mailto:<?= $message['email'] ?>>"><?= $message['email'] ?></a></p>
	<p><strong>Date: </strong> <?= htmlentities($message['date']) ?></p>
	<p><strong>Subject: </strong> <?= htmlentities($message['subject']) ?></p>
	<p><strong>IP: </strong> <?= $message['ip'] ?></p>
	<p style="max-width: 600px;"><strong>Message:</strong><br><?= $message['message'] ?></p>

</div>