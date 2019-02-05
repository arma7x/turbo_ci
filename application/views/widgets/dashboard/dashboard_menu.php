<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

                <div id="navCollapsed" class="collapse navbar-collapse-dashboard shadow">
                    <div class="bg-light p-2 border border-top-0 border-right-0 border-left-0">
                      <div class="row p-2">
                          <div class="col col-3">
                            <img class="rounded-circle shadow-sm" width="45px" height="45px" src="<?php echo $this->container->user['avatar'] ?>"/>
                          </div>
                          <div class="col col-9">
                              <div class="ml-1 font-weight-bolder" style="overflow:hidden">
                                  <?php echo $this->container->user['username'] ?>
                              </div>
                              <div class="ml-1 small">
                                  <?php echo $this->container->user['role_alias'] ?>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div id="accordionDashboard" class="accordion bg-light">
                      <a class="dropdown-item py-2<?php echo '/'.$this->uri->uri_string() == '/dashboard/index' || '/'.$this->uri->uri_string() == '/dashboard' ? ' active' : ''?>" onclick="navigate('/dashboard/index')"><i class="material-icons">&#xe30d;</i> <?php echo lang('H_DASHBOARD');?></a>
                      <div class="dropdown-divider"></div>
                      <div class="dropdown-item py-2" data-toggle="collapse" data-target="#collapse_manage_user" aria-expanded="false" aria-controls="collapse_manage_user">
                        <div id="heading_manage_user">
                            <i class="material-icons">&#xe2c9;</i> <?php echo lang('H_MANAGE_USERS');?>
                        </div>
                        <div id="collapse_manage_user" class="<?php echo $this->uri->segment(2) == 'manage_user' ? '' : 'collapse'?>" aria-labelledby="heading_manage_user" data-parent="#accordionDashboard">
                          <div>
                              <div class="dropdown-divider"></div>
                              <ul class="small nav nav-pills flex-column" style="overflow:hidden">
                                  <li class="nav-item">
                                    <?php if((int) $this->container->user['role'] <= 1): ?>
                                    <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/dashboard/manage_user/index' ? ' active' : ''?>" onclick="navigate('/dashboard/manage_user/index')"><i class="material-icons">&#xe7ef;</i> <?php echo lang('H_MANAGE_USERS');?></a>
                                      <?php endif; ?>
                                  </li>
                                  <li class="nav-item">
                                    <?php if((int) $this->container->user['role'] === 0): ?>
                                    <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/dashboard/manage_user/ui_register' ? ' active' : ''?>" onclick="navigate('/dashboard/manage_user/ui_register')"><i class="material-icons">&#xe7fe;</i> <?php echo lang('H_ADD_USER');?></a>
                                  <?php endif; ?>
                                  </li>
                              </ul>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown-divider d-lg-none"></div>
                      <div class="dropdown-item py-2 d-lg-none" data-toggle="collapse" data-target="#collapseLang" aria-expanded="false" aria-controls="collapseLang">
                        <div id="headingLang">
                            <i class="material-icons">&#xe8e2;</i> <?php echo lang('L_LANGUAGE') ?>
                        </div>
                        <div id="collapseLang" class="collapse" aria-labelledby="headingLang" data-parent="#accordionDashboard">
                          <div>
                              <div class="dropdown-divider"></div>
                              <ul class="small nav nav-pills flex-column" style="overflow:hidden">
                                  <li class="nav-item">
                                    <a class="nav-link" onclick="change_language('english')" data-turbolinks="false"><?php echo lang('L_ENGLISH_LANG') ?></a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" onclick="change_language('malay')" data-turbolinks="false"><?php echo lang('L_MALAY_LANG') ?></a>
                                  </li>
                              </ul>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown-divider d-lg-none"></div>
                      <?php if($this->container->user !== NULL): ?>
                        <a class="dropdown-item py-2 d-lg-none" onclick="logout()"data-turbolinks="false"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGOUT');?></a>
                      <?php endif; ?>
                    </div>
                </div>