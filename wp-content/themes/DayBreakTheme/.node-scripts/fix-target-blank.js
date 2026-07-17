import fs from "fs";
import { glob } from "glob";

console.log("🚀 Script started");

const patterns = ["**/*.php", "**/*.html"];
let updatedCount = 0;

for (const pattern of patterns) {
  const files = await glob(pattern, { ignore: "node_modules/**" });
  console.log(`🔍 Pattern "${pattern}" matched ${files.length} files`);

  for (const file of files) {
    let content = fs.readFileSync(file, "utf8");

    // 💡 これがシンプル版：
    // target="_blank" の直後に rel="noopener noreferrer" を追加する
    const updated = content.replace(
      /target="_blank"(?![^>]*rel=)/gi,
      'target="_blank" rel="noopener noreferrer"'
    );

    if (updated !== content) {
      fs.writeFileSync(file, updated);
      console.log(`✅ Updated: ${file}`);
      updatedCount++;
    }
  }
}

console.log(`🎉 Done! ${updatedCount} file(s) updated.`);
