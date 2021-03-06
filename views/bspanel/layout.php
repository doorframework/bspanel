<?
/* @var $app Door\Core\Application */
use Door\Bootstrap\Helper\Icons;
$h = $app->html;

?><!DOCTYPE html>
<html>
<head><?=$headers?></head>
<body>
	<header class="navbar">
		<div class="container-fluid expanded-panel">
			<div class="row">
				<div id="logo" class="col-xs-12 col-sm-2">
					<a href="/admin">Door</a>
				</div>
				<div id="top-panel" class="col-xs-12 col-sm-10">
					<div class="row">
						<div class="col-sm-4">
							<a href="#" class="show-sidebar">
							  <i class="fa fa-bars"></i>
							</a>
							
							<!--<div id="search">
								<input type="text" placeholder="search"/>
								<i class="fa fa-search"></i>
							</div>-->
						</div>
						<div class="col-xs-4 col-sm-8 top-panel-right">
							<ul class="nav navbar-nav pull-right panel-menu">
								<!--<li class="hidden-xs">
									<a href="index.html" class="modal-link">
										<i class="fa fa-bell"></i>
										<span class="badge">7</span>
									</a>
								</li>
								<li class="hidden-xs">
									<a class="ajax-link" href="ajax/calendar.html">
										<i class="fa fa-calendar"></i>
										<span class="badge">7</span>
									</a>
								</li>
								<li class="hidden-xs">
									<a href="ajax/page_messages.html" class="ajax-link">
										<i class="fa fa-envelope"></i>
										<span class="badge">7</span>
									</a>
								</li>-->
								<li>
										<a class="" href="/">Перейти на сайт</a>

								</li>
								<li>|</li>
								<li>
										<a class="" href="/admin/logout">Выйти</a>

								</li>
								<!--<li class="dropdown">
									<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
										<div class="avatar">
											<img src="img/avatar.jpg" class="img-rounded" alt="avatar" />
										</div>
										<i class="fa fa-angle-down pull-right"></i>
										<div class="user-mini pull-right">
											<span class="welcome">Welcome,</span>
											<span>Jane Devoops</span>
										</div>
									</a>
									<ul class="dropdown-menu">
										<li>
											<a href="#">
												<i class="fa fa-user"></i>
												<span class="hidden-sm text">Profile</span>
											</a>
										</li>
										<li>
											<a href="ajax/page_messages.html" class="ajax-link">
												<i class="fa fa-envelope"></i>
												<span class="hidden-sm text">Messages</span>
											</a>
										</li>
										<li>
											<a href="ajax/gallery_simple.html" class="ajax-link">
												<i class="fa fa-picture-o"></i>
												<span class="hidden-sm text">Albums</span>
											</a>
										</li>
										<li>
											<a href="ajax/calendar.html" class="ajax-link">
												<i class="fa fa-tasks"></i>
												<span class="hidden-sm text">Tasks</span>
											</a>
										</li>
										<li>
											<a href="#">
												<i class="fa fa-cog"></i>
												<span class="hidden-sm text">Settings</span>
											</a>
										</li>
										<li>
											<a href="#">
												<i class="fa fa-power-off"></i>
												<span class="hidden-sm text">Logout</span>
											</a>
										</li>
									</ul>
								</li>-->
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!--End Header-->
	<!--Start Container-->
	<div id="main" class="container-fluid">
		<div class="row">
			<div id="sidebar-left" class="col-xs-2 col-sm-2">
				<ul class="nav main-menu">
					<?
						$uri = $app->initial_request()->uri();
						$active_uri = null;
						foreach($admin_menu as $item)
						{
							if(strpos($uri, $item->uri) === 0)
							{
								if($active_uri === null || strlen($item->uri) > $active_uri)
								{
									$active_uri = $item->uri;
								}
							}
						}
					?>
					<? foreach($admin_menu as $item) { ?>
					<?
						$class = "";
						if($item->uri == $active_uri)
						{
							$class = "active";
						}
					?>
					 <li>
						<?=$h->anchor($item->uri,Icons::show($item->icon)." <span class='hidden-xs'>{$item->name}</span>",array('class' => $class))?>
					 </li>					
					<? } ?>
													
				</ul>
				
				<div class="powered">Powered by <a href='https://github.com/doorframework'>Door Framework</a></div>
			</div>
			<!--Start Content-->
			<div id="content" class="col-xs-12 col-sm-10">
				<?=$content?>
			</div>
			<!--End Content-->
		</div>
	</div>	
	
</body>
</html>
