<div class="container-fluid">
	<div id="page-login" class="row">
		<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="box">
				<form method="POST" action="">
					<div class="box-content">
						<div class="text-center">
							<h3 class="page-header">
								<? if($is_error) { ?>
								<div class="text-danger">Не удалось войти</div>
								<? } else { ?>
								Вход в панель управления
								<? } ?>
							</h3>
						</div>
						<div class="form-group">
							<label class="control-label">Username</label>
							<input type="text" class="form-control" name="username" value="<?=$username?>" />
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control" name="password" />
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-primary">Войти</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>