<?php

namespace WPLog;

use WowLog\Vendor\Monolog\Handler\NativeMailerHandler;
use WowLog\Vendor\Monolog\Formatter\LineFormatter;

class MailHandler extends NativeMailerHandler {
    protected function send( string $content, array $records ): void {
		$content = wordwrap( $content, $this->maxColumnWidth );
		$headers = ltrim( implode( "\r\n", $this->headers ) . "\r\n", "\r\n" );
		$headers .= 'Content-type: ' . $this->getContentType() . '; charset=' . $this->getEncoding() . "\r\n";

		if ( $this->getContentType() == 'text/html' && false === strpos($headers, 'MIME-Version:') ) {
			$headers .= 'MIME-Version: 1.0' . "\r\n";
		}

		$subject = $this->subject;

		if ( $records ) {
			$subjectFormatter = new LineFormatter( $this->subject );
			$subject = $subjectFormatter->format( $this->getHighestRecord( $records ) );
		}

        wp_mail( $this->to, $subject, $content );
	}
}