import sys
import torch
import rembg
from PIL import Image
from tsr.system import TSR

if len(sys.argv) < 3:
    sys.exit("Gunakan: python3 run_triposr.py <input_image> <output_glb>")

input_img_path = sys.argv[1]
output_glb_path = sys.argv[2]

device = "cpu"

try:
    image = Image.open(input_img_path)
    image_nobg = rembg.remove(image)

    model = TSR.from_pretrained(
        "stabilityai/TripoSR",
        config_name="config.yaml",
        weight_name="model.ckpt"
    )
    model.to(device)

    with torch.no_grad():
        scene_codes = model(image_nobg, device=device)
        meshes = model.extract_mesh(scene_codes)

        meshes[0].export(output_glb_path)

except Exception as e:
    with open(output_glb_path + ".error", "w") as f:
        f.write(str(e))