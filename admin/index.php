<?php
require_once('../php/authorize.php');
?>
<?php include('header.php'); ?>


            <?php
                require_once('../php/util.php');

                // Connect to the database
                $dbc = mysqli_connect(host,user, password, database);

                // Retrieve the score data from MySQL
                $query = "SELECT  * FROM activity ORDER BY activity_register_time desc";
                $data = mysqli_query($dbc, $query);
                $query1 = "SELECT  * FROM user";
                $data1 = mysqli_query($dbc, $query1);
                $data_person = $data1;
            ?>
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="#">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">管理界面</a>
					</li>
				</ul>
			</div>
			<div class="sortable row-fluid">
				<a data-rel="tooltip" class="well span3 top-block" href="#">
					<span class="icon32 icon-red icon-user"></span>
					<div>成员总数</div>
					<div><?php echo mysqli_num_rows($data1);?></div>
				</a>

				<a data-rel="tooltip" class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-star-on"></span>
					<div>活动总数</div>
					<div><?php echo mysqli_num_rows($data);?></div>
				</a>
			</div>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-star"></i> 活动</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>活动标题</th>
								  <th>活动日期</th>
								  <th>联系邮箱</th>
								  <th>状态</th>
								  <th>操作</th>
							  </tr>
						  </thead>
						  <tbody>
						  <?php
                            while ($row = mysqli_fetch_array($data)) {
                                    $user_id = $row['user_id'];
                                    $query1 = "SELECT  * FROM user where user_id = $user_id ";
                                    $data1 = mysqli_query($dbc, $query1);
                                    $row1 = mysqli_fetch_array($data1);

                                    $activity_id = $row['activity_id'];
                                    $query2 = "SELECT  * FROM activity_photo where activity_id = $activity_id ";
                                    $data2 = mysqli_query($dbc, $query2);
                                    $row2 = mysqli_fetch_array($data2);
                                    echo '<tr><td>'.$row['name'].'</td>';
                                    echo '<td class="center">'.$row['activity_register_time'].'</td>';
                                    echo '<td class="center">'.$row1['email'].'</td>';
                                    echo '<td class="center">';
                                    if ($row['approved'] == '0') {
                                        echo '<span class="label label-warning">等待审核</span>';
                                    } else if($row['approved'] == '1'){
                                        echo '<span class="label label-success">活动通过</span>';
                                    }
                                    echo '<td class="center">'.
                                           '<a class="btn btn-info" href="../activity.php?activity='.$row['activity_id'].'">'.
                                           '<i class="icon-zoom-in icon-white"></i> 查看</a> ';
                                    echo '<a class="btn btn-success" href="../php/approve-activity.php?id=' . $row['activity_id'] . '&amp;date=' . $row['activity_register_time'] .
                                        '&amp;email=' . $row1['email'] . '&amp;title=' . $row['name'] .'&amp;place=' . $row['site'].'&amp;introduce='.
                                        $row['introduce'].'&amp;photo=' . $row2['photo'] .'"><i class="icon-edit icon-white" ></i> 通过</a> ';
                                    echo '<a class="btn btn-danger" href="../php/remove-activity.php?id=' . $row['activity_id'] . '&amp;date=' . $row['activity_register_time'] .
                                        '&amp;email=' . $row1['email'] . '&amp;title=' . $row['name']. '&amp;photo=' . $row2['photo'] .'"><i class="icon-trash icon-white" ></i> 删除</a></td></tr>';
                            }
                          ?>
						  </tbody>
					  </table>
					</div>
				</div><!--/span-->
			</div><!--/row-->
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> 成员</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
				    <div class="box-content">
				    	<table class="table table-striped table-bordered bootstrap-datatable datatable">
				    	  <thead>
				    		  <tr>
				    			  <th>用户昵称</th>
				    			  <th>注册时间</th>
				    			  <th>联系邮箱</th>
				    			  <th>性别</th>
				    			  <th>学院</th>
				    			  <th>操作</th>
				    		  </tr>
				    	  </thead>
				    	  <tbody>
				    	  <?php
                            while ($row1 = mysqli_fetch_array($data_person)) {
                                    echo '<tr><td>'.$row1['nickname'].'</td>';
                                    echo '<td class="center">'.$row1['register_date'].'</td>';
                                    echo '<td class="center">'.$row1['email'].'</td>';
                                    echo '<td class="center">';
                                    if ($row1['sex'] == '1') {
                                        echo '男';
                                    } else if($row1['sex'] == '0'){
                                        echo '女';
                                    }
                                    echo '</td>';
                                    echo '<td class="center">'.$row1['academy'].'</td>';
                                    echo '<td class="center"> <a class="btn btn-danger" href= "#"><i class="icon-trash icon-white"></i> 删除</a></td>';
                                    echo '</tr>';
                            }
                          ?>
				    	  </tbody>
				      </table>
				    </div>
			    </div><!--/span-->

			</div><!--/row-->

<?php include('footer.php'); ?>
