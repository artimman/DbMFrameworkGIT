    <?php
        // Navigation
        include(BASE_DIRECTORY . 'application/View/_include/navigation.html.php');
        // Page data
        !empty($data['data.user']) ? $user = $data['data.user'] : $user = null;
        $user->avatar ? $avatar = $user->avatar : $avatar = 'no-avatar.png';
    ?>
    <!-- Breadcrumb -->
    <section class="container">
        <nav class="bg-light rounded-3 px-3 py-2 mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?php echo path(); ?>" class="link-secondary">Home</a></li>
                <li class="breadcrumb-item active">User profile</li>
            </ol>
        </nav>
    </section>
    <!-- Main Content -->
    <main>
        <div class="container mb-5">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="<?php echo path('public/images/avatar/') . $avatar; ?>" alt="avatar" class="rounded-circle img-fluid" style="width:150px">
                            <h5 class="my-3"><?php echo $user->fullname; ?></h5>
                            <p class="text-muted mb-1"><?php echo $user->profession; ?></p>
                            <p class="text-muted mb-4"><?php echo $user->business; ?></p>
                            <div class="d-flex justify-content-center mb-2">
                                <button type="button" class="btn btn-primary">Follow</button>
                                <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?php echo $user->fullname; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">E-mail</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?php echo $user->email; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
