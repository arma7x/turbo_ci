<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

                <div id="navCollapsed" class="bg-light collapse navbar-collapse-dashboard shadow">
                    <div class="bg-light p-2 border border-top-0 border-right-0 border-left-0">
                      <div class="text-center pt-3">
                        <a onclick="goHome()" data-turbolinks="false">
                            <img class="logo" style="width:60px;height:60px" src="/static/img/android-chrome-192x192.png" alt="logo"/>
                            <h1 class="text-primary"><?php echo $this->container['app_name'] ?></h1>
                        </a>
                      </div>
                      <div class="row p-2">
                          <div class="col col-3">
                            <img id="avatar" src="<?php echo $this->container['user']['avatar'] ?>" class="rounded-circle shadow-sm" width="45px" height="45px"/>
                          </div>
                          <div class="col col-9">
                              <div class="ml-1 font-weight-bolder" style="overflow:hidden">
                                  <?php echo $this->container['user']['username'] ?>
                              </div>
                              <div class="ml-1 small">
                                  <?php echo $this->container['user']['role_alias'] ?>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div id="accordionDashboard" class="accordion bg-light">
                    <div class="autoscroll">
                      <div class="dropdown-divider d-none d-lg-block"></div>
                      <a class="dropdown-item p-2<?php echo '/'.$this->uri->uri_string() == '/dashboard/index' || '/'.$this->uri->uri_string() == '/dashboard' ? ' active' : ''?>" onclick="navigate('/dashboard/index')"><i class="material-icons">&#xe30d;</i> <?php echo lang('H_DASHBOARD');?></a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item p-2<?php echo $this->uri->segment(2) == 'manage_user' ? ' active' : ''?>" onclick="navigate('/dashboard/manage_user')"><i class="material-icons">&#xe2c9;</i> <?php echo lang('H_MANAGE_USERS');?></a>
                      <div class="dropdown-divider d-lg-none"></div>
                      <div class="dropdown-item p-0 d-lg-none">
                        <div id="headingLang" class="p-2" data-toggle="collapse" data-target="#collapseLang" aria-expanded="false" aria-controls="collapseLang">
                            <i class="material-icons">&#xe8e2;</i> <?php echo lang('L_LANGUAGE') ?>
                        </div>
                        <div id="collapseLang" class="collapse" aria-labelledby="headingLang" data-parent="#accordionDashboard">
                          <div class="m-1">
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
                      <?php if($this->container['user'] !== NULL): ?>
                        <a class="dropdown-item p-2 d-lg-none" onclick="logout()"data-turbolinks="false"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGOUT');?></a>
                      <?php endif; ?>
                    </div>
                    </div>
                </div>
