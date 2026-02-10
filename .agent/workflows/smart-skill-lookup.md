---
description: Automatically identify and utilize relevant skills from the antigravity-awesome-skills/skills library
---

1. **Analyze Request**: Analyze the user's current request to identify key technologies, patterns, or specific tasks (e.g., "Laravel", "React", "Testing", "Security", "Import functionality").
2. **Search Skills**:
   - Use `find_by_name` in the `antigravity-awesome-skills/skills` directory to locate potentially relevant skill folders.
   - Example query: `find_by_name(SearchDirectory="c:/Project/e-saraban/antigravity-awesome-skills/skills", Pattern="*laravel*")`
3. **Select Skill**: Review the search results and select the most relevant skill directory.
4. **Read Instructions**: Locate the `SKILL.md` file within the selected skill directory and read it using `view_file`.
5. **Apply Context**: Read and internalize the provided guidelines, best practices, and patterns from the skill documentation.
6. **Execute**: Proceed to solve the user's original request, ensuring all steps adhere to the guidelines learned from the skill.
