<template>
  <div class="card mt-4">
    <div class="card-header">New Posts</div>
    <div class="card-body">
      <div
        v-if="status_msg"
        :class="{'alert-success':status, 'alert-danger':!status}"
        class="alert"
        role="alert"
      >{{status_msg}}</div>
      <form>
        <div class="form-group">
          <label for="examoleFormControlInput">Title</label>
          <input
            v-model="title"
            type="text"
            class="form-control"
            id="title"
            placeholder="Post Title"
            required
          />
        </div>
        <div class="form-group">
          <label for="examoleFormControlTextarea1">Post Content</label>
          <textarea
            v-model="body"
            class="form-control"
            id="post-content"
            placeholder="Post Title"
            rows="3"
            required
          ></textarea>
        </div>
        <div class>
          <el-upload
            action="/"
            list-type="picture-card"
            :on-preview="handlePictureCardPreview"
            :on-progress="uploadImageList"
            :auto-upload="false"
          >
            <i class="el-icon-plus"></i>
          </el-upload>
          <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt />
          </el-dialog>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <button
        type="button"
        @click="createPost"
        class="btn btn-success"
      >{{isCreatedPost ? 'Posting...' : 'Create Post'}}</button>
    </div>
  </div>
</template>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
</style>


<script>
import { mapActions } from "vuex";
export default {
  name: "create-post",
  props: ["posts"],
  data() {
    return {
      dialogImageUrl: "",
      dialogVisible: false,
      imageList: [],
      status: "",
      title: "",
      body: ""
    };
  },
  computed: {
    ...mapActions(["getAllPosts"])
  },

  methods: {
    updateImageList(file) {
      this.imageList.push(file.raw);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    showNotification(message) {
      this.status_msg = message;
      setTimeout(() => {
        this.status_msg = "";
      }, 3000);
    },
    ValidateForm() {
      if (!this.title) {
        this.status = false;
        this.showNotification("Post title cannot be empty");
        return false;
      }
      if (this.imageList.length < 1) {
        this.status = false;
        this.showNotification("You need to select an image");
        return false;
      }
      return true;
    },
    createPost(e) {
      e.preventDefault();
      if (!this.ValidateForm()) {
        return false;
      }
      this.isCreatingPost = true;
      let formData = new FormData();

      formData.append("title", this.title);
      formData.append("body", this.body);

      $.each(this.imageList, function(key, image) {
        formData.append(`images[${key}]`, image);
      });

      api
        .post("/post/create_post", formData, {
          headers: { "content-type": "multipart/form-data" }
        })
        .then(res => {
          this.title = this.body = "";
          this.status = true;
          this.showNotification("post Successfully Created");
          this.isCreatingPost = false;
          this.getAllPosts();
        });
    }
  }
};
</script>