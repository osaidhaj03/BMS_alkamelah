<?php

namespace App\Http\Controllers;

use App\Models\FeedbackComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackComplaintController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:complaint,suggestion,feedback,inquiry',
            'category' => 'required|in:book,author,search,page,general',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|integer',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB
        ], [
            'type.required' => 'يرجى اختيار نوع البلاغ',
            'category.required' => 'يرجى اختيار التصنيف',
            'subject.required' => 'الموضوع مطلوب',
            'message.required' => 'الرسالة مطلوبة',
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب إدخال بريد إلكتروني صحيح',
            'attachment.mimes' => 'نوع الملف غير مدعوم',
            'attachment.max' => 'حجم الملف يجب أن لا يتجاوز 5 ميجابايت',
        ]);

        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('feedback-attachments', 'public');
            $validated['attachment_path'] = $path;
        }

        FeedbackComplaint::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال بلاغك بنجاح! سنقوم بمراجعته في أقرب وقت. شكراً لك',
            ]);
        }

        return back()->with('success', 'تم إرسال بلاغك بنجاح! سنقوم بمراجعته في أقرب وقت. شكراً لك');
    }
}
