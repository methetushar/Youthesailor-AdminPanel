<template>
    <div>
        <div class="box box-success" v-if="!$root.spinner">
            <div class="box-header with-border">
                <h3 class="box-title">{{ model + " Create" }}</h3>

                <!--============ Add or Back Button Start ============-->
                <AddOrBackButton
                    :route="model + '.index'"
                    :portion="model"
                    :icon="'arrow-left'"
                />
                <!--============ Add or Back Button End ============-->
            </div>

            <div class="box-body box-min-height">
                <!--============ Form Start ============-->
                <div class="row">
                    <form
                        v-on:submit.prevent="submit"
                        enctype="multipart/form-data"
                        id="form"
                        class="form-row col-12"
                    >
                        <!------------ Single Input ------------>
                        <div
                            class="form-group col-3"
                            :class="{
                                'has-error': validation.hasError('data.category_id'),
                                'has-success': data.category_id,
                              }">
                            <label>Category</label>
                            <select
                                v-model="data.category_id"
                                name="product[category_id]"
                                class="form-control form-control-sm"
                            >
                                <option value>---Select one---</option>
                                <option
                                    v-for="(value, name, index) in extraData.categories"
                                    :key="index"
                                    v-bind:value="value"
                                >
                                    {{ value }}
                                </option>
                            </select>
                            <span class="help-block">{{
                                    validation.firstError("data.category_id")
                                }}</span>
                        </div>
                        <!------------ Single Input ------------>

                        <!------------ Single Input ------------>
                        <div
                            class="form-group col-3"
                            :class="{
                            'has-error': validation.hasError('data.pro_name'),
                            'has-success': data.pro_name,
                          }"
                        >
                            <label>Product Name</label>
                            <input
                                type="text"
                                v-model="data.pro_name"
                                name="product[pro_name]"
                                class="form-control form-control-sm"
                                placeholder="Product Name"
                            />
                            <span class="help-block">{{
                                    validation.firstError("data.pro_name")
                                }}</span>
                        </div>
                        <!------------ Single Input ------------>
                        <div
                            class="form-group col-2"
                            :class="{'has-error': validation.hasError('data.amount'),  'has-success': data.amount, }">
                            <label>Product Price</label>
                            <input
                                type="number"
                                v-model="data.amount"
                                name="product[amount]"
                                class="form-control form-control-sm"
                                placeholder="Product Price"
                            />
                            <span class="help-block">{{
                                    validation.firstError("data.amount")
                                }}</span>
                        </div>

                        <div class="form-group col-3">
                            <label>Product Image</label>
                            <div class="row">
                                <div class="col-2">
                                    <img
                                        class="img-responsive choose-file-size"
                                        :src="data.image ? data.image : $root.noimage"
                                        alt="picture"
                                    />
                                </div>
                                <div class="col-10">
                                    <b-form-file
                                        accept="image/*"
                                        id="file-small34"
                                        size="sm"
                                        class="file2"
                                        v-on:change="onFileChange($event, 'image', 'file2')"
                                        drop-placeholder="Drop file here"
                                    ></b-form-file>
                                </div>
                                <div class="col-12">
                                  <span class="help-block text-danger">
                                      {{ validation.firstError("data.image")}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group col-3">
                          <label>Cash on Delivery Available</label>
                          <div class="row col-12">
                            <b-form-checkbox
                              class="col-form-label-sm col-6"
                              v-model="data.cash_on_delivery"
                              name="product[cash_on_delivery]"
                              value="1"
                              :unchecked-value="null"
                              >Yes</b-form-checkbox
                            >
                            <b-form-checkbox
                              class="col-form-label-sm col-6"
                              v-model="data.cash_on_delivery"
                              name="product[cash_on_delivery]"
                              value="0"
                              :unchecked-value="null"
                              >No</b-form-checkbox
                            >
                          </div>
                        </div> -->
                        <!------------ Single Input ------------>
                        <div class="form-group col-4">
                            <label>Product Type</label>
                            <div class="row col-12">
                                <b-form-checkbox
                                    class="col-form-label-sm col-6"
                                    v-model="data.pro_type"
                                    name="product[pro_type]"
                                    value="single"
                                    :unchecked-value="null"
                                >Single Product
                                </b-form-checkbox
                                >
                                <b-form-checkbox
                                    class="col-form-label-sm col-6"
                                    v-model="data.pro_type"
                                    name="product[pro_type]"
                                    value="variation"
                                    :unchecked-value="null"
                                >Variation Product
                                </b-form-checkbox
                                >
                            </div>
                        </div>

                        <div
                            class="form-group col-3"
                            :class="{
                                          'has-error': validation.hasError('data.qty'),
                                          'has-success': data.qty,
                                        }">
                            <label>Product QTY</label>
                            <input
                                type="number"
                                v-model="data.qty"
                                name="product[qty]"
                                class="form-control form-control-sm"
                                placeholder="Product QTY"
                            />
                            <span class="help-block">{{ validation.firstError("data.qty") }}</span>
                        </div>

                        <div class="col-12" v-if="data.pro_type == 'variation'">
                            <div class="row">
                                <div class="col-6 m-0 p-0">
                                    <div class="row col-12 mb-2 m-0 p-0"
                                         v-for="(pro, index) in data.variation" :key="index + 1">
                                        <!------------ Single Input ------------>
                                        <div class="form-group col-4">
                                            <label>Color</label>
                                            <input
                                                v-model="pro.color_id"
                                                :name="'variation[' + index + '][color_id]'"
                                                class="form-control form-control-sm"
                                                placeholder="#00000"
                                            >
                                        </div>
                                        <div class="form-group col-4">
                                            <label >Size</label>
                                            <select
                                                v-model="pro.size_id"
                                                :name="'variation[' + index + '][size_id]'"
                                                class="form-control form-control-sm"
                                            >
                                                <option value>---Select one---</option>
                                                <option
                                                    v-for="(value, name, index) in extraData.sizes"
                                                    :key="index"
                                                    v-bind:value="value"
                                                >
                                                    {{ value }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-1 mt-4" v-if="index > 0">
                                            <button
                                                type="button"
                                                @click="data.variation.splice(index, 1)"
                                                class="btn btn-sm btn-danger"><i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="col-1 mt-4" v-if="index + 1 == Object.keys(data.variation).length">
                                            <button
                                                type="button"
                                                @click="data.variation.push({ color_id: '', size_id: '' })"
                                                class="btn btn-sm btn-info">Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 m-0 p-0">
                                    <div class="row col-md-12 m-0 p-0" v-if="data.pro_type == 'variation'">
                                        <div class="form-group col-12 m-0 p-0"
                                             v-for="(img, imgIndex) in data.product_images"
                                             :key="'img' + (imgIndex + 1)">
                                            <!------------ Single Input ------------>
                                            <label>Product Image</label>
                                            <div class="row">
                                                <div class="form-group col-2">
                                                    <img
                                                        class="img-responsive"
                                                        :src="img.image ? img.image : $root.noimage"
                                                        alt="picture"
                                                        style="width: 60px; height: 55px"
                                                    />
                                                </div>
                                                <div class="form-group col-6">
                                                    <b-form-file
                                                        accept="image/*"
                                                        :id="'file-smalla-' + imgIndex"
                                                        size="sm"
                                                        :class="'file' + imgIndex"
                                                        v-on:change="
                                                        onFileChangeProduct(
                                                          $event,
                                                          imgIndex,
                                                          'file',
                                                          img.id
                                                        )
                                                      "
                                                        drop-placeholder="Drop file here"
                                                    ></b-form-file>
                                                </div>
                                                <div class="form-group col-1" v-if="imgIndex > 0">
                                                    <button
                                                        @click="removeImage(imgIndex)"
                                                        type="button"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="form-group col-1 " v-if="">
                                                    <button
                                                        v-if="img.image"
                                                        type="button"
                                                        @click="data.product_images.push({ image: '' })"
                                                        class="btn btn-sm btn-info">
                                                        Add Image
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <label class="col-12">Description</label>
                            <editor :editorModel="'description'"/>
                        </div>




                        <!-------------- button -------------->
                        <div class="col-12 mb-3 mt-4">
                            <button type="submit" class="btn btn-sm btn-info">Submit</button>
                        </div>
                        <!-------------- button -------------->
                    </form>
                </div>
                <!--============ Form End ============-->
            </div>
        </div>
<!--        <span v-if="data.product_images">{{ checkArr() }}</span>-->
        <pre>
            {{ data }}
        </pre>
    </div>
</template>

<script>
// define model name
const model = "product";

export default {
    data() {
        return {
            model: model,
            data: {
                image: "",
                category_id: "",
                pro_type: "single",
                variation: [{color_id: "", size_id: ""}],
                product_images: [{image: "", id: ""}],
            },
            image: {},
            product_images: [],
            extraData: {
                categories: ['Yeouth','Men','Women','Kid','Gift'],
                sizes: ['XS','S','M','L','XL'],
                colors: [],
            },
            errors: {},
        };
    },
    methods: {
        submit: function () {
            const error = this.validation.countErrors();
            this.$validate().then((res) => {
                // If there is an error
                if (error > 0) {
                    this.notification(
                        "You need to fill " + error + " more empty mandatory fields",
                        "warning"
                    );
                    return false;
                }

                // If there is no error
                if (res) {
                    var form = document.getElementById("form");
                    var formData = new FormData(form);
                    if (this.image.image) {
                        formData.append("product[image]", this.image.image);
                    } else {
                        formData.append("product[image]", "");
                    }
                    if (this.data.description) {
                        formData.append("product[description]", this.data.description);
                    } else {
                        formData.append("product[description]", "");
                    }


                    if (Object.keys(this.product_images).length > 0) {
                        $.each(this.product_images, function (key, proImage) {
                            console.log(proImage);
                            if (proImage["image"]) {
                                formData.append(
                                    "product_images[" + key + "][images]",
                                    proImage["image"]
                                );
                                formData.append(
                                    "product_images[" + key + "][id]",
                                    proImage["id"]
                                );
                            }
                        });
                    }
                    formData.append(
                        "exist_product_images",
                        JSON.stringify(this.data.product_images)
                    );
                    if (this.data.id) {
                        this.update(this.model, formData, this.data.slug, "image");
                    } else {
                        this.store(this.model, formData);
                    }
                }
            });
        },
        onFileChange(e, model, fileClass, pdf = null) {
            this.showImage(e, model, model, fileClass, pdf);
        },
        onFileChangeProduct(e, index, fileClass, id = null) {
            console.log(index, fileClass);
            $("." + fileClass + index)
                .children(".custom-file-label")
                .html("File attached");
            let files = e.target.files || e.dataTransfer.files;
            if (files.length) {
                this.product_images.push({image: e.target.files[0], id: id});
                this.data.product_images[index].image = URL.createObjectURL(
                    e.target.files[0]
                );
            }

            data.variation.push({ color_id: '', size_id: '' })

        },
        removeImage(index) {
            this.data.product_images.splice(index, 1);
            this.product_images.splice(index, 1);
        },
        checkArr() {
            if (Object.keys(this.data.product_images).length == 0) {
                this.data.product_images = [{image: "", id: ""}];
            }
            if (Object.keys(this.data.variation).length == 0) {
                this.data.variation = [{color_id: "", size_id: ""}];
            }
        },
    },
    created() {
        if (this.$route.params.id) {
            this.setBreadcrumbs(this.model, "edit"); // Set Breadcrumbs
            this.get_data(this.model, this.$route.params.id, "data"); // get data for edit
            this.show.pro_desc = true;
            this.show.pro_sku = true;
            this.show.pro_img = true;
            this.show.indeterminate = false;
            this.show.indeterminate1 = false;
            this.show.indeterminate2 = false;
        } else {
            this.setBreadcrumbs(this.model, "create"); // Set Breadcrumbs
            setTimeout(() => (this.$root.spinner = false), 200);
        }
        this.get_paginate_data("category", {allData: true}, "categories"); // get contents
    },
    beforeCreate() {
        this.$root.spinner = true;
    },

    // ================== validation rule for form ==================
    validators: {
        "data.pro_name": function (value = null) {
            return Validator.value(value).required("Product Name is required");
        },
        "data.category_id": function (value = null) {
            return Validator.value(value).required("Category Name is required");
        },
        "data.pro_type": function (value = null) {
            return Validator.value(value).required("Product Type is required");
        },
        "data.image": function (value = null) {
            return Validator.value(value).required("Product Image is required");
        },
    },
};
</script>
