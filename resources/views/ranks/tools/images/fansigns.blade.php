@extends('layouts.app-b')

@section('template_title', 'Fansigns')

@section('content')
<div class="row">
	<div class="col-12 mt-3">
		<div class="card card-primary border-0 rounded-0 no-shadow">
			<h5 class="card-header">Add more fansigns</h5>
			<div class="card-body p-2">
				<form method="post" @submit.prevent="submitFSimages">
					@csrf
					<div class="form-group">
						<label>Select Files :</label>
						<div class="input-group">
							<div class="custom-file">
								<input multiple name="images_file" type="file" class="custom-file-input text-truncate rounded-0" id="exampleInputFile" @change="onFileChangeFS" accept="image/x-png,image/gif,image/jpeg">
								<label class="custom-file-label fimages_label text-truncate rounded-0" for="exampleInputFile">Choose File</label>
							</div>
						</div>	
					</div>
					<button class="btn btn-sm btn-success rounded-0" type="submit">UPLOAD</button>
					<div class="row no-gutters mt-3">
						<div class="col-12">
							<div class="alert alert-warning rounded-0">
								All images will be resized into <b>350 pixels by 500 pixels</b> after upload. Choose wisely.
							</div>
						</div>
					</div>
					<div class="row no-gutters mt-3 fs-images" v-if="images.length > 0">
						<div class="col-3 p-1 rounded-0 mb-1" v-for="(img, index) in images" :data-index="index">
							<img :src="img.src" class="w-100">
							<button class="btn btn-sm btn-block btn-outline-danger mt-2 rounded-0" type="button" @click.prevent="deselectImage(index)">DELETE</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 mt-3">
		<fs-list :images="images_uploaded_fs"></fs-list>
	</div>
</div>
@stop