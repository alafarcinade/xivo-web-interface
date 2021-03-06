<?php

#
# XiVO Web-Interface
# Copyright (C) 2006-2014  Avencall
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#

$url = &$this->get_module('url');

$client_url = $this->bbf('download_soft_url_xivo-client',XIVO_DOWNLOAD_URL);
$doc_url = $this->bbf('url_xivo-documentation',XIVO_DOC_URL);
$blog_url = $this->bbf('url_xivo-blog',XIVO_BLOG_URL);

?>
<div class="b-infos">
	<h3 class="sb-top xspan">
		<span class="span-left">&nbsp;</span>
		<span class="span-center"><?=$this->bbf('title_content_name');?></span>
		<span class="span-right">&nbsp;</span>
	</h3>
	<div class="sb-content">
		<dl>
			<dt><?=$this->bbf('info_download_xivo-client');?></dt>
			<dd><?=$url->href_html($client_url,
					       $client_url,
					       null,
					       'target="_blank"',
					       null,
					       false,
					       null,
					       false,
					       false);?>
			<dt><?=$this->bbf('info_xivo-documentation');?></dt>
			<dd><?=$url->href_html($doc_url,
					       $doc_url,
					       null,
					       'target="_blank"',
					       null,
					       false,
					       null,
					       false,
					       false);?>
			<dt><?=$this->bbf('info_xivo-blog');?></dt>
			<dd><?=$url->href_html($blog_url,
					       $blog_url,
					       null,
					       'target="_blank"',
					       null,
					       false,
					       null,
					       false,
					       false);?>
		</dl>
	</div>
	<div class="sb-foot xspan">
		<span class="span-left">&nbsp;</span>
		<span class="span-center">&nbsp;</span>
		<span class="span-right">&nbsp;</span>
	</div>
</div>
