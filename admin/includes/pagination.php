							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">						
									<li class="page-item">
									  <a class="page-link btn <?php if($page_num<=1) echo 'disabled'; ?>" href="<?php echo $page_name."?page=".($page_num-1);?>" aria-label="Previous" >
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									  </a>
									</li>
									<?php
										for($i=$page_num;$i>0;$i--){																						
									?>
										<li class="page-item"><a class="page-link" href="home.php?page=<?php echo abs($page_num-$i);?>"><?php echo ($page_num-$i)+1;?></a></li>
									
									<?php } ?>
									<li class="page-item" ><a class="page-link" href="home.php?page=<?php echo $page_num;?>" style="background: #ddd;"><?php echo $page_num+1;?></a></li>
									<?php
										for($i=1;$i<=2;$i++){
											if(($page_num+$i)*$limit > $totalRow) break;
											
									?>
										<li class="page-item"><a class="page-link" href="home.php?page=<?php echo $page_num+$i;?>"><?php echo $page_num+1+$i;?></a></li>
									<?php } ?>
									<li class="page-item">
									
									  <a class="page-link btn <?php if((($page_num)*$limit)>=$totalRow) echo 'disabled'; ?>" href="<?php echo $page_name."?page=".($page_num+1);?>" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									  </a>									  
									</li>
								</ul>
							</nav>