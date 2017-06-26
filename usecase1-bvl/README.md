# Use case 1: BVL location database

The objective of this use case is to test the model using the location database of the Behindertenverband Leipzig e.V. (BVL). Point of view is from a structure and to potential ICF elements.

**Important links:**
1. Current web version of the location database maintained by the BVL: http://www.le-online.de/inhalt.htm
2. About the BVL: http://www.le-online.de/infos.htm
3. Working repository containing enriched CSV file, on which this use case is based on: https://github.com/AKSW/transform-bvl-pages-to-csv-file
4. There are [**interim results**](https://github.com/k00ni/from-object-to-icf/blob/master/usecase1-bvl/result.txt) already available

### Main questions

1. **Which locations in Leipzig have structures, which impede somebody?** 
2. **And how are impeded ones characterized (described by the [ICF](http://www.who.int/classifications/icf/en/))?**


### How to interpret the interim results?

Interim results contain entries which look like to the following. Current software checks for steps and swing door as entrance door. Based on my model, i can conclude (based on the data) that, for instance, Forum 1813 is not (fully) accessible by people with missing/damaged b7 (foots) and/or damaged/missing d450 as well as d465.

```
Location: Forum 1813 - Straße des 18. Oktober 100, 04299, Leipzig: 
|
`-steps
  `-- has requirements:
  |   `-- f4: Steigfähigkeit (Beine und Füße) related to ICF elements:
          `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Beine und Füße)
          `-- d450 Gehen
  |   `-- f5: Fortbewegung related to ICF elements:
          `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Beine und Füße)
          `-- d450 Gehen
          `-- d465 Sich unter Verwendung von Geräten/Ausrüstung fortbewegen
  |
  `-- requires availability/functionality of the following ICF elements:
      `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Beine und Füße)
      `-- d450 Gehen
      `-- d465 Sich unter Verwendung von Geräten/Ausrüstung fortbewegen
|
`-swing-door
  `-- has requirements:
  |   `-- e1: Nutzerhöhe related to ICF elements: 
          `-- x101 Nutzerhöhe (Person + Ausrüstung)
  |   `-- e2: Nutzerbreite related to ICF elements: 
          `-- x102 Nutzerbreite (Person + Ausrüstung)
  |   `-- f1: Greiffähgikeit (Hand) related to ICF elements:
          `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Hände)
  |   `-- f2: Ziehbewegung (Hand) related to ICF elements:
          `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Hände)
  |   `-- f3: Drückbewegung (Hand) related to ICF elements:
          `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Hände)
  |
  `-- requires availability/functionality of the following ICF elements:
      `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Beine und Füße)
      `-- d450 Gehen
      `-- d465 Sich unter Verwendung von Geräten/Ausrüstung fortbewegen
      `-- x101 Nutzerhöhe (Person + Ausrüstung)
      `-- x102 Nutzerbreite (Person + Ausrüstung)
      `-- b7 Neuromuskuloskeletale und Bewegungsbezogene Funktionen (Hände)
```
