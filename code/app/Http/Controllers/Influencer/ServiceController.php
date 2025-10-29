<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceGallery;
use App\Models\Tag;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {

    public function all() {
        $pageTitle = 'All Services';
        $services  = $this->serviceData();
        $influencer = authInfluencer();
        // dd($services);
        return view('templates.basic.influencer.service.list', compact('pageTitle', 'services', 'influencer'));
        //return view($this->activeTemplate . 'influencer.service.list', compact('pageTitle', 'services'));
    }

    public function create() {
        $pageTitle = "Create New Service";
        $tags = Tag::all();
        return view('templates.basic.influencer.service.create', compact('pageTitle', 'tags'));
        //return view($this->activeTemplate . 'influencer.service.create', compact('pageTitle'));
    }

    public function edit($id) {
        $pageTitle = "Update Service";
        $service   = Service::where('influencer_id', authInfluencerId())->with('gallery', 'tags')->findOrFail($id);
        $tags = Tag::all();
        $images = [];

        foreach ($service->gallery as $gallery) {
            $img['id']  = $gallery->id;
            $img['src'] = getImage(getFilePath('service') . '/' . $gallery->image);
            $images[]   = $img;
        }
        return view('templates.basic.influencer.service.create', compact('pageTitle', 'tags', 'images', 'service'));
        return view($this->activeTemplate . 'influencer.service.create', compact('pageTitle', 'service', 'images'));
    }

    public function destroy($id) {
        $service = Service::where('influencer_id', authInfluencerId())->findOrFail($id);
        if(!$service){
            $notify[] = ['error', 'Service not found'];
            return back()->withNotify($notify);
        }
        $service->status = -1; // -1 means deleted
        $service->save();
        $notify[] = ['success', 'Service deleted successfully'];
        return back()->withNotify($notify);
    }
    public function store(Request $request, $id = 0) {
        // dd($request);
        $this->validation($request->all(), $id)->validate();
        $influencer = authInfluencer();
        $general = gs();
        $service = $this->insertService($general, $id,$request);
        // dd($service);
        $this->insertTag($service, $id);

        if ($id) {
            $oldImages   = $service->gallery->pluck('id')->toArray();
            $imageRemove = array_values(array_diff($oldImages, $request->old ?? []));

            foreach ($imageRemove as $remove) {
                $singleImage = ServiceGallery::find($remove);
                $location    = getFilePath('service');
                fileManager()->removeFile($location . '/' . $singleImage->image);
                fileManager()->removeFile($location . '/thumb_' . $singleImage->image);
                $singleImage->delete();
            }

            $notification = 'Service updated successfully';
        }

        $this->serviceImages($request, $service);

        if (!$id) {
            $adminNotification                = new AdminNotification();
            $adminNotification->influencer_id = $influencer->id;
            $adminNotification->title         = 'New service created by ' . $influencer->username;
            $adminNotification->click_url     = urlPath('admin.service.detail', $service->id);
            $adminNotification->save();
            $notification = 'Service created successfully';
        }
        //  dd($service);
        $notify[] = ['success', $notification];
        return redirect()->route('influencer.service.all')->withNotify($notify);    }

    protected function validation(array $data, $id) {

        $imageValidation = !$id ? 'required' : 'nullable';

        $validate = Validator::make($data, [
            'category_id'  => 'nullable|integer|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|gte:0',
            'tags'         => 'required|array|min:1',
            'key_points'   => 'nullable|array',
            'key_points.*' => 'string|max:255',
            'image'        => [$imageValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'images'       => 'nullable|array',
            'images.*'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'key_points.*.required' => 'Key points is required',
        ]);

        return $validate;
    }

    protected function insertService($general, $id,$request) {

        $influencerId = authInfluencerId();
        $service_from_database=Service::where('influencer_id', $influencerId)->where('id',$id)->count();
        // dd($i);
        // $request      = request();

        if ($id && $service_from_database > 0) {
            $service  = Service::where('influencer_id', $influencerId)->findOrFail($id);
            // dd($service);
            $oldImage = $service->image;
        } else {
            $service  = new Service();
            $oldImage = null;
        }

        $service->influencer_id = $influencerId;
        $service->category_id   = $request->category_id ?? null;
        // dd($request->title);
        $service->title         = $request->title;
        $service->price         = $request->price;
        $service->description   = $request->description;
        $service->key_points    = $request->key_points ?? [];
        $service->status        = $general->service_approve == 1 ? 1 : 0;
        $service->save();

        if ($request->hasFile('image')) {
            try {
                $destinationPath =  getFilePath('service');

                // Ensure the destination directory exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file=$request->image;

                // Generate a unique file name
                $fileName = uniqid() . '_' . $file->getClientOriginalName();

                // Move the file to the public/attachments directory
                $file->move($destinationPath, $fileName);

                // Add the relative file path to the array
                // $arrFile[] = $fileName;
                // dd(1);
                $service->image = $fileName;
                $service->save();
                // $service->image = fileUploader($request->image, getFilePath('service'), getFileSize('service'), $oldImage, getFileThumb('service'));
            } catch (\Exception$exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }

        }else{
            $service->image =  $oldImage;
            $service->save();
        }

        $service->save();
        return $service;
    }

    protected function insertTag($service, $id) {
        $request = request();

        foreach ($request->tags as $tag) {
            $tagExist = Tag::where('name', $tag)->first();

            if ($tagExist) {
                $tagId[] = $tagExist->id;
            } else {
                $newTag       = new Tag();
                $newTag->name = $tag;
                $newTag->save();
                $tagId[] = $newTag->id;
            }

        }

        if ($id) {
            $service->tags()->sync($tagId);
        } else {
            $service->tags()->attach($tagId);
        }

    }

    protected function serviceImages($request, $service) {

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $image) {

                if (isset($request->imageId[$key])) {
                    $singleImage = ServiceGallery::find($request->imageId[$key]);
                    $location    = getFilePath('service');
                    fileManager()->removeFile($location . '/' . $singleImage->image);
                    fileManager()->removeFile($location . '/thumb_' . $singleImage->image);
                    $singleImage->delete();

                    $newImage           = fileUploader($image, getFilePath('service'), getFileSize('service'), null, getFileThumb('service'));
                    $singleImage->image = $newImage;
                    $singleImage->save();
                } else {
                    try {
                        $newImage = fileUploader($image, getFilePath('service'), getFileSize('service'), null, getFileThumb('service'));
                    } catch (\Exception$exp) {
                        $notify[] = ['error', 'Couldn\'t upload your image.'];
                        return back()->withNotify($notify);
                    }

                    $gallery             = new ServiceGallery();
                    $gallery->service_id = $service->id;
                    $gallery->image      = $newImage;
                    $gallery->save();
                }

            }

        }

    }

    public function pending() {
        $pageTitle = 'Pending Services';
        $services  = $this->serviceData('pending');
        $influencer = authInfluencer();
        return view('templates.basic.influencer.service.list', compact('pageTitle', 'services', 'influencer'));
    }

    public function approved() {
        $pageTitle = 'Approved Services';
        $services  = $this->serviceData('approved');
        $influencer = authInfluencer();
        return view('templates.basic.influencer.service.list', compact('pageTitle', 'services', 'influencer'));
    }

    public function rejected() {
        $pageTitle = 'Rejected Services';
        $services  = $this->serviceData('rejected');
        $influencer = authInfluencer();
        return view('templates.basic.influencer.service.list', compact('pageTitle', 'services', 'influencer'));
    }

    protected function serviceData($scope = null) {

        if ($scope) {
            $services = Service::$scope();
        } else {
            $services = Service::query();
        }

        $request = request();

        $services = $services->where([
            ['influencer_id', authInfluencerId()],
            ['status', '!=', -1]]);

        if ($request->search) {
            $search   = $request->search;
            $services = $services->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            });
        }

        return $services->with('category')->with('tags')->withCount('totalOrder', 'completeOrder')->latest()->paginate(getPaginate());
    }

    public function orders(Request $request, $id) {
        $pageTitle = 'Service Order List';

        $service = Service::approved()->where('influencer_id', authInfluencerId())->findOrFail($id);
        $orders  = Order::where('service_id', $service->id);

        $request = request();

        if ($request->search) {
            $search = request()->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('order_no', $search)->orWhereHas('user', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $orders = $orders->with('user')->latest()->paginate(getPaginate());

        return view('templates.basic.influencer.service.orders', compact('pageTitle', 'orders', 'service'));
    }
    public function delete($locale, $id){
        $service=Service::whereId($id)->first();
        $service->category()->delete();
        $service->gallery()->delete();
        $service->tags()->delete();
        $service->reviews()->delete();

        $service->delete();
        return redirect()->back();
    }

}
