<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Admin
                <small>Subheading</small>
            </h1>
            <?php 

            // $new_user = new User();
            // $new_user->username     = 'ellie_bauer';
            // $new_user->password     = '1801';
            // $new_user->first_name   = 'Ellie';
            // $new_user->last_name    = 'Bauer';

            // $new_user->create();
            
            $user = User::find_user_by_id(5);
            $user->delete_user();

            ?>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Blank Page
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->