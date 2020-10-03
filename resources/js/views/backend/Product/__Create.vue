<template>
  <div>
    <div class="box box-success" v-if="!$root.spinner">
      <div class="box-header with-border">
        <h3 class="box-title">{{ model + " Create" }}</h3>

        <!--============ Add or Back Button Start ============-->
        <AddOrBackButton :route="model +'.index'" :portion="model" :icon="'arrow-left'" />
        <!--============ Add or Back Button End ============-->
      </div>

      <div class="box-body box-min-height">
        <!--============ Form Start ============-->
        <div class="row">
          <form v-on:submit.prevent="submit" enctype="multipart/form-data" id="form" class="form-row col-12">
            <!------------ Single Input ------------>
            <div
              class="form-group col-4"
              :class="{ 'has-error': validation.hasError('data.product_name'), 'has-success': data.product_name }"
            >
              <label>Product Name</label>
              <input
                type="text"
                v-model="data.product_name"
                name="product_name"
                class="form-control form-control-sm"
                placeholder="Name"
              />
              <span class="help-block">{{ validation.firstError('data.product_name') }}</span>
            </div>

              <div
                  class="form-group col-3"
                  :class="{ 'has-error': validation.hasError('data.product_price'), 'has-success': data.product_price }"
              >
                  <label>Product Price</label>
                  <input
                      type="text"
                      v-model="data.product_price"
                      name="product_price"
                      class="form-control form-control-sm"
                      placeholder="Product Price"
                  />
                  <span class="help-block">{{ validation.firstError('data.product_price') }}</span>
              </div>

              <div
                  class="form-group col-3"
                  :class="{ 'has-error': validation.hasError('data.category'), 'has-success': data.category }"
              >
                  <label>Category</label>
                  <select name="category" id="" v-model="data.category" class="form-control form-control-sm">
                      <option :value="undefined" selected disabled>Select On</option>
                      <option value="men">Men</option>
                      <option value="kids">Kid</option>
                      <option value="gift">Gift</option>
                      <option value="women">Women</option>
                  </select>
                  <span class="help-block">{{ validation.firstError('data.category') }}</span>
              </div>

              <!------------ Single Input ------------>

              <div class="col-md-12 row m-0 p-0" v-for="(input,index) in inputs" :key="index">
                  <!------------ Single Input ------------>
                  <div
                      class="form-group col-3"
                      :class="{ 'has-error': validation.hasError('data.product_image'), 'has-success': data.product_image }"
                  >
                      <label>{{ index + 1}} Product Image </label>
                      <div class="row">
                          <div class="col-2">
                              <img
                                  class="img-responsive rounded-circle choose-file-size"
                                  :src="data.product_image?data.product_image:$root.noimage"
                                  alt="picture"
                              />
                          </div>
                          <div class="col-10">
                              <b-form-file
                                  accept="image/*"
                                  id="file-small"
                                  size="sm"
                                  class="file1"
                                  v-on:change="onFileChange($event,'product_image','file1')"
                                  drop-placeholder="Drop file here"
                              ></b-form-file>
                          </div>
                      </div>
                      <span class="help-block">{{ validation.firstError('data.product_image') }}</span>
                  </div>
                  <!------------ Single Input ------------>
                  <div
                      class="form-group col-3"
                      :class="{ 'has-error': validation.hasError('input.product_color'), 'has-success': input.product_color }"
                  >
                      <label>{{ index + 1}} Product Color </label>
                      <input
                          type="text"
                          :name="product_color +index + 1"
                          :value="input.product_color"
                          class="form-control form-control-sm"
                          placeholder="#00000"
                      />
                      <span class="help-block">{{ validation.firstError('input.product_color') }}</span>
                  </div>

                  <!------------ Single Input ------------>
                  <div
                      class="form-group col-3"
                      :class="{ 'has-error': validation.hasError('input.product_size'), 'has-success': input.product_size }"
                  >
                      <label>{{ index + 1}} Product Size </label>
                      <input
                          type="text"
                          :name="product_size+index + 1"
                          :value="input.product_size"
                          class="form-control form-control-sm"
                          placeholder="Product Size"
                      />
                      <span class="help-block">{{ validation.firstError('input.product_size') }}</span>
                  </div>

                  <!------------ Single Input ------------>


                  <div class="form-group col-3 text-center">
                      <label for=""></label>
                      <button type="button" @click="AddField(index)" v-show="index == inputs.length-1" class="btn btn-success btn-sm mt-3">
                          <i class="fa fa-plus"></i>
                      </button>
                      <button type="button" @click="RemoveField(index)" v-show="index || (!index && inputs.length > 1)"  class="btn btn-danger btn-sm mt-3">
                          <i class="fa fa-times"></i>
                      </button>
                  </div>

              </div>
              <!------------ Single Input ------------>
              <div class="form-row col-12">
                  <label class="col-12">Product Description</label>
                  <div class="col-12">
                      <editor :editorModel="'description'" />
                  </div>
              </div>
              <br>
              <div
                  class="form-group col-3"
                  :class="{ 'has-error': validation.hasError('data.available'), 'has-success': data.available }"
              >
                  <label>Status</label>
                  <div class="row mt-2">
                      <div class="col-6">
                          <input type="radio" name="status" v-model="data.status" :value="'yes'" /> Published
                      </div>
                      <div class="col-6">
                          <input type="radio" name="status" v-model="data.status" :value="'no'" /> Pending
                      </div>
                  </div>
                  <span class="help-block">{{ validation.firstError('data.status') }}</span>
              </div>
            <!-------------- button -------------->
            <div class="col-12 mb-3 mt-2">
              <button type="submit" class="btn btn-sm btn-info">Submit</button>
            </div>
            <!-------------- button -------------->
          </form>
        </div>
        <!--============ Form End ============-->
      </div>
    </div>
  </div>
</template>

<script>
// define model name
const model = "product";

export default {
  data() {
    return {
      model: model,
        select:undefined,
        image: {},
        data: { product_image: "", status: "a" },
      errors: {},
      inputs:[
          {
              product_image:'',
          }
      ],

    };
  },
  methods: {
    submit: function() {
        const error = this.validation.countErrors();
        this.$validate().then(res => {
            // If there is an error
            if (error > 0) {
                this.notification(
                    "You need to fill " + error + " more empty mandatory fields",
                    "warning"
                );
                return false;
            }

            if (res) {
                var form = document.getElementById("form");
                var formData = new FormData(form);
                if (this.image.product_image) {
                    formData.append("product_image", this.image.product_image);
                } else {
                    formData.append("product_image", "");
                }
                if (this.data.description) {
                    formData.append("description", this.data.description);
                } else {
                    formData.append("description", "");
                }
                if (this.data.id) {
                    this.update(this.model, formData, this.data.id, "image");
                } else {
                    this.store(this.model, formData);
                }
            }
        });
    },
      onFileChange(e, model, fileClass, pdf = null) {
          this.showImage(e, model, model, fileClass, pdf); // 1st data image, second show image
      },



      AddField(index){
          this.inputs.push({product_image:this.image.product_image})
      },

      RemoveField(index){
          this.inputs.splice(index,1)
      }
    },
  created() {
    if (this.$route.params.id) {
      this.setBreadcrumbs(this.model, "edit"); // Set Breadcrumbs
      this.get_data(this.model, this.$route.params.id, "data"); // get data for edit
    } else {
      this.setBreadcrumbs(this.model, "create"); // Set Breadcrumbs
      setTimeout(() => (this.$root.spinner = false), 200);
    }
  },
  beforeCreate() {
    this.$root.spinner = true;
  },

  // ================== validation rule for form ==================
  validators: {
    "data.name": function(value = null) {
      //return Validator.value(value).required("Name is required");
    }
  }
};
</script>
