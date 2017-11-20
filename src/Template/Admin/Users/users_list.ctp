<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                    <?php //echo $this->Html->link('Upload CSV', array('controller' => 'Users', 'action' => 'import_data'), array('class' => 'btn btn-info', 'style' => 'float: right;')); ?>
                </div>
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo $this->Paginator->sort('device_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('device_type'); ?></th>
                                <th><?php echo $this->Paginator->sort('first_name'); ?></th>
                                <th><?php echo $this->Paginator->sort('last_name'); ?></th>
                                <th><?php echo $this->Paginator->sort('username'); ?></th>
                                <th><?php echo $this->Paginator->sort('email'); ?></th>
                                <th><?php echo $this->Paginator->sort('token'); ?></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u){ ?>
                                <tr>
                                     <td><?php echo $u['device_id']; ?></td>
                                    <td><?php echo $u['device_type']; ?></td>		
                                    <td><?php echo $u['first_name']; ?></td>
                                    <td><?php echo $u['last_name']; ?></td>
                                    <td><?php echo $u['username']; ?></td>
                                    <td><?php echo $u['email']; ?></td>
                                    <td><?php echo $u['token']; ?></td>
                                    <td>
                                        <?php 
                                            echo $this->Html->link('Edit ', array('controller'=>'users','action'=>'add',$u['id'])).'/';
                                            echo $this->Form->postLink(' Delete',['controller'=>'users','action' => 'delete', $u['id']],['escape' => false,'confirm' => __('Are you sure, you want to delete this record?')]);
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>                       
                    </table>                   
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                <?php echo $this->Paginator->counter(['format' => 'Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total']); ?>
                            </div>                                
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                <ul class="pagination" style="margin-top: 10px;">
                                    <li class="paginate_button previous disabled" id="example2_previous">
                                        <?php echo $this->Paginator->prev('Previous', array('escape' => false), null, array('class' => 'prev disabled prv', 'escape' => false)); ?>                                        
                                    </li>
                                    <li class="paginate_button active">
                                        <?php echo $this->Paginator->numbers(array('separator' => '', 'currentClass' => 'active', 'currentTag' => 'a', 'escape' => false)); ?>
                                    </li>                                  
                                    <li class="paginate_button next" id="example2_next">
                                        <?php echo $this->Paginator->next('Next', array('escape' => false), null, array('class' => 'next disabled nxt', 'escape' => false)); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>

</section>