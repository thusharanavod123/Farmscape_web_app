from flask import Flask, request, jsonify, send_file
from flask_cors import CORS
import torch
from PIL import Image, ImageDraw, ImageFont
import io
import easyocr  # Use EasyOCR for text and number detection
import numpy as np

app = Flask(__name__)
CORS(app)


# Initialize EasyOCR reader
reader = easyocr.Reader(['en'])  # Specify the languages for OCR


# Load YOLOv5 model
model = torch.hub.load('ultralytics/yolov5', 'custom', path='yolov5s.pt')

@app.route('/predict', methods=['POST'])
def predict():
    if 'file' not in request.files:
        return "No file part", 400
    
    file = request.files['file']
    if file.filename == '':
        return "No selected file", 400
    
    # Read image from file-like object
    image = Image.open(io.BytesIO(file.read()))
    
    # Perform object detection
    results = model(image)
    
    # Process detection results and annotate image
    annotated_image = annotate_image(image, results)
    
    # Extract text from the image
    text_results = extract_numbers(image)
    
    # Save annotated image to a temporary file
    temp_image_path = 'C:/Users/User/Desktop/yolo model/yolov5/tmp/annotated_image.jpg'  
    annotated_image.save(temp_image_path)
    
    # Return detection results and send annotated image
    return jsonify({
        'results': results.pandas().xyxy[0].to_dict(orient="records"),
        'text_results': text_results,

        'annotated_image_url': f'http://localhost:5000/annotated_image'
    })

@app.route('/annotated_image')
def get_annotated_image():
    temp_image_path = 'C:/Users/User/Desktop/yolo model/yolov5/tmp/annotated_image.jpg'  
    return send_file(temp_image_path, mimetype='image/jpeg')

def annotate_image(original_image, results):
    annotated_image = original_image.copy()
    draw = ImageDraw.Draw(annotated_image)
    font = ImageFont.load_default()
    
    for result in results.pandas().xyxy[0].to_dict(orient="records"):
        label = result['name']
        confidence = result['confidence']
        xmin, ymin, xmax, ymax = result['xmin'], result['ymin'], result['xmax'], result['ymax']
        
        #checking whether it is a farm animal
        if label == "cow":
        # Draw bounding box
            draw.rectangle([(xmin, ymin), (xmax, ymax)], outline='green', width=4)
        
        # Add label and confidence
            draw.text((xmin, ymin - 10), f'{label} {confidence:.2f}', fill='red', font=font)
            
    del draw
    return annotated_image   

     
def extract_numbers(image):
    # Convert PIL image to RGB if needed
    image = image.convert('RGB')
    
    # Convert PIL image to numpy array (numpy nthuw support krn nh easyocr ek)
    image_np = np.array(image)
    
    # Perform OCR using EasyOCR
    text_results = reader.readtext(image_np)
    
    # Filter out non-numeric text and return only numbers
    number_results = [{'text': res[1], 'confidence': res[2]} for res in text_results if res[1].isdigit()]
    return number_results
   

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
