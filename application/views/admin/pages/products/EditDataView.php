<div id="PageActive" class="content-wrapper">

  <!-- Headbar -->
  <ui-header title="<?= $title ?>" caption="<?= $caption ?>">
    <ol class="breadcrumb">
      <li><a href="<?= base_url('admin') ?>"><i class="fa fa-dashboard"></i></a></li>
      <li><a href="<?= base_url('admin/products/all-data') ?>"><?= $title ?></a></li>
      <li class="active"><?= $caption ?></li>
    </ol>
  </ui-header>
  <!-- End Headbar -->

  <!-- Content -->
  <form @submit.prevent="saveData" role="form">
    <div class="content">
      <div class="row">

        <!-- Left Column -->
        <div class="col-md-9">

          <!-- Widget -->
          <ui-widget title="<?= lang('EDITOR') ?>" :loading="loading">
            <div class="box-body">

              <ui-alert :data-source="result"></ui-alert>

              <ui-textbox v-model="form.title"><?= lang('TITLE') ?></ui-textbox>
              <ui-wysi v-model="form.content"><?= lang('CONTENT') ?></ui-wysi>

            </div>
          </ui-widget>
          <!-- End Widget -->

          <!-- Widget -->
          <ui-widget title="<?= lang('IMAGES') ?>" :loading="loading">
            <div class="box-body">
              <div class="row">

                <div class="col-md-3">
                  <div>
                    <ui-image v-model="form.featuredImage" placeholder="Size -"></ui-image>
                  </div>
                  <div style="padding-top: 8px;">
                    <ui-button @click="addImage" type="button" icon="plus"></ui-button>
                  </div>
                </div>

                <div v-for="(image, i) in form.images" class="col-md-3">
                  <div>
                    <ui-image v-model="form.images[i]" placeholder="Size -"></ui-image>
                  </div>
                  <div style="padding-top: 8px;">
                    <ui-button @click="addImage" type="button" icon="plus"></ui-button>
                    <ui-button @click="removeImage(i)"
                    type="button"
                    icon="close"
                    bs-type="danger"></ui-button>
                  </div>
                </div>

              </div>
            </div>
          </ui-widget>
          <!-- End Widget -->

        </div>
        <!-- End Left Column -->

        <!-- Right Column -->
        <div class="col-md-3">

          <ui-widget title="<?= lang('ACTION') ?>">

            <div class="box-body">
              <ui-select v-model="form.publicationId" :data-source="rsPublication" data-field="publication">
                <?= lang('PUBLICATION') ?>
              </ui-select>
              <ui-select v-model="form.productCategoryId" :data-source="rsProductCategory" data-field="category">
                <?= lang('CATEGORY') ?>
              </ui-select>
              <ui-textbox v-model="form.price">
                <?= lang('PRICE') ?>
              </ui-textbox>
            </div>

            <div class="box-footer">

              <ui-button :loading="loading" icon="save">
                <?= lang('SAVE') ?>
              </ui-button>

              <div class="pull-right">
                <ui-link @click="addNewData" icon="plus">
                  <?= lang('ADD_NEW_DATA') ?>
                </ui-link>
              </div>
            </div>

          </ui-widget>

        </div>
        <!-- End Right Column -->

      </div>
    </div>
    <!-- End Content -->
  </form>

</div>

<script>

  var vm = new Vue({
    el: '#PageActive',

    /************************
    <id="_data">
    ************************/
    data: {
      loading: false,
      result: $api.result(),

      rsPublication: [],
      rsProductCategory: [],

      form: {
        publicationId: '',
        productCategoryId: '',
        title: '',
        content: '',
        featuredImage: '',
        images: [],
        price: '',
      },

    },

    /************************
    <id="_created">
    ************************/
    created: function () {
      $('#ProductsMenu').addClass('active');
      this.refreshData();
    },

    /************************
    <id="_methods">
    ************************/
    methods: {

      /************************
      <id="_refreshData">
      ************************/
      refreshData: function () {
        this.loading = true;

        var url = 'PublicationController/readAllData';
        var data = {
          orderBy: 'id',
        };

        $api.post(url, data, function (result) {

          this.rsPublication = result.data;

          var url = 'ProductCategoryController/readAllData';
          var data = {
            orderBy: 'id',
          };

          $api.post(url, data, function (result) {

            this.rsProductCategory = result.data;

            var url = 'ProductController/readOneData/<?= $_SEGMENT[0] ?>';

            $api.post(url, function (result) {
              this.form = result.data;
              this.loading = false;
            }.bind(this));

          }.bind(this));

        }.bind(this));

      },

      /************************
      <id="_addNewData">
      ************************/
      addNewData: function () {
        window.location = admin_url('products/add-new-data');
      },

      /************************
      <id="_saveData">
      ************************/
      saveData: function () {

        this.loading = true;

        var url = 'ProductController/updateData';
        var data = this.form;

        $api.post(url, data, function (result) {

          this.result = result;
          this.loading = false;

          if (result.state == 'success') {
            $splash.show(result.message, function () {
              this.form = result.data;
            }.bind(this));
          }

        }.bind(this));
      },

      /************************
      <id="_addImage">
      ************************/
      addImage: function () {
        this.form.images.push('');
      },

      /************************
      <id="_removeImage">
      ************************/
      removeImage: function (index) {
        // this.form.images.splice(i, 1);
        var i = -1;
        var images = this.form.images;
        this.form.images = [];
        this.form.images = images.filter(image => {
          i++;
          return i != index;
        });
      },

    },
  });
</script>
