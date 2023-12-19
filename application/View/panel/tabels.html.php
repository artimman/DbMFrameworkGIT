    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
            include('../application/View/_include/panel_sidebar.html.php');
        ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php
                    include('../application/View/_include/panel_topbar.html.php');
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent">
                                <li class="breadcrumb-item active h3" aria-current="page">Tables</li>
                            </ol>
                        </nav>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm font-weight-bold"><i class="fas fa-plus text-white-50 mr-2"></i>Create</a>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableOne" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Created</th>
                                            <th class="noSort">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Aiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Biger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Ciger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>2011/04/25</td>
                                            <td>Action</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="mt-4 mb-2">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a href="https://datatables.net" target="_blank">official DataTables documentation</a>.</p>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End of Main Content -->
            <?php
                include('../application/View/_include/panel_footer.html.php');
            ?>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <?php
        include('../application/View/_include/panel_logout.html.php');
    ?>
