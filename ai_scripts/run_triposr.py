import sys, json, traceback
import torch
import rembg
from PIL import Image
from tsr.system import TSR

if len(sys.argv) < 3:
    sys.exit("Gunakan: python3 run_triposr.py <input_image> <output_glb>")

input_img_path = sys.argv[1]
output_glb_path = sys.argv[2]
progress_file = output_glb_path + ".progress"

def report(prog, text):
    try:
        with open(progress_file, "w") as f:
            json.dump({"progress": prog, "text": text}, f)
    except: pass

try:
    report(10, "Menghapus background gambar...")

    torch.set_num_threads(2) 
    
    image = Image.open(input_img_path)
    image_nobg = rembg.remove(image)

    report(30, "Memuat memori kecerdasan buatan (TripoSR)...")
    device = "cpu"
    model = TSR.from_pretrained(
        "stabilityai/TripoSR",
        config_name="config.yaml",
        weight_name="model.ckpt"
    )
    model.to(device)

    report(60, "Menebak bentuk 3D dari gambar (Proses Berat)...")
    with torch.no_grad():
        scene_codes = model(image_nobg, device=device)
        
        report(80, "Mengekstrak jaring 3D (Mesh)...")
        meshes = model.extract_mesh(scene_codes, resolution=224)

        report(95, "Menyimpan model hasil Imajinasi AI...")
        meshes[0].export(output_glb_path)
        
    report(100, "Selesai!")

except Exception as e:
    with open(output_glb_path + ".error", "w") as f:
        f.write(traceback.format_exc())